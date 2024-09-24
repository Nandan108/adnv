<?php

namespace App\Models\Commercialdoc;

use App\Enums\CommercialdocEventType;
use App\Enums\CommercialdocStatus;
use App\Models\Commercialdoc;
use App\Models\CommercialdocInfo\FlightLine;
use App\Models\CommercialdocInfo\HeaderResId;
use App\Models\CommercialdocInfo\HotelLine;
use App\Models\CommercialdocInfo\TransferLine;
use App\Models\CommercialdocInfo\TravelerLine;
use App\Models\Reservation;
use App\Models\CommercialDocInfo;


use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Quote extends Commercialdoc
{
    public static function makeFromTripReservation(Reservation $reservation, array $clientInfo): self
    {

        return DB::transaction(function () use ($reservation, $clientInfo) {
            $quote = self::create([
                'doc_id'         => self::getNewDocID(),
                'status'         => CommercialdocStatus::INITIAL_QUOTE_CREATED,
                'currency_code'  => 'CHF',
                'reservation_id' => $reservation->id,
                'object_type'    => 'trip',
                ...$clientInfo,
            ]);

            $infoLines = [];
            $addInfoLine = function ($class, $data) use ($quote, &$ords, &$infoLines) {
                $ords[$class] = ($ord = $ords[$class] ?? 0) + 1;
                $infoLines[$class][] = $line = new $class(['ord' => $ord, 'data' => $data]);
                $line->doc()->associate($quote)->save();
            };


            $resTotals = $reservation->getTotals();

            $getTripTotals = function ($filterOnPersonType) use ($resTotals) {
                $totals = $resTotals->filter(fn($tot) => $filterOnPersonType($tot->typePerson['vol']));
                return [
                    $totals->count(),
                    $totals->sum(fn($tot) => $tot->total['vol'] + $tot->total['transfert'] + $tot->total['chambre']),
                ];
            };

            $sortByKeys = collect($reservation->participants)
                ->map(fn($t) => (100 * (int)!$t->adulte) + $t->idx)
                ->sort();

            $sortedTravelers = collect($reservation->participants)
                ->sortBy(fn($t) => (100 * (int)!$t->adulte) + $t->idx)
                ->values();

            foreach ($sortedTravelers as $ord => $traveler) {
                // title for adults, fallback to birthdate for children
                $nameComplement = $traveler->titre ?: 'CHD (' .
                    Carbon::parse($traveler->date_naissance)->translatedFormat('d F Y') . ')';

                $addInfoLine(TravelerLine::class, [
                    'name' => "$traveler->nom $traveler->prenom $nameComplement",
                ]);
            }

            if ($hotel = $reservation->chambre?->hotel) {
                $addInfoLine(TravelerLine::class, ['name' => 'Réservation hotel n°']);

                $meal = $reservation->prestations->
                    filter(fn($prest) => $prest->type?->is_meal)->first();

                $addInfoLine(HotelLine::class, [
                    'checkin'   => $reservation->datesHotelStay[0],
                    'checkout'  => $reservation->datesHotelStay[1],
                    'hotel'     => "$hotel->nom\n" . ($hotel->lieu->lieu ?: $hotel->lieu->ville),
                    'room_type' => $reservation->chambre->nom_chambre,
                    'meal_type' => $meal?->type?->name,
                ]);

                $description = "Séjour adulte $reservation->nbNuitsHotel nuits à l'hôtel \"$hotel->nom\"";
            } else {
                $apt         = $reservation->prixVol->vol->apt_arrive;
                $nomAeroport = "$apt->code_aeroport / {$apt->lieu->ville} ({$apt->aeroport})";
                $description = "Vol aller-retour pour $nomAeroport";
            }

            $items_ord = 0;

            [$totCount, $totSum] = $getTripTotals(fn($person) => $person === 'adulte');
            $items[]             = $quote->items()->create([
                'ord'          => $items_ord++,
                'description'  => $description,
                'qtty'         => $totCount,
                'unitprice'    => $totSum / $totCount,
                'discount_pct' => 0, // TODO input pre-discount unitprice, then show discount
                'section'      => 'primary',
            ]);

            [$totCount, $totSum] = $getTripTotals(fn($person) => $person !== 'adulte');
            if ($totCount) {
                $items[] = $quote->items()->create([
                    'ord'          => $items_ord++,
                    'description'  => "Séjour enfant",
                    'qtty'         => $totCount,
                    'unitprice'    => $totSum / $totCount,
                    'discount_pct' => 0,
                    'section'      => 'primary',
                ]);
            }

            if ($flight = $reservation->prixVol?->vol) {
                $ord = 0;
                $addInfoLine(FlightLine::class, [
                    'date'         => $reservation->date_depart,
                    'airline'      => $flight->airline->company,
                    'origin'       => $flight->code_apt_depart,
                    'dest'         => $flight->code_apt_transit ?: $flight->code_apt_arrive,
                    'arr_next_day' => $flight->code_apt_transit ? 0 : $flight->arrivalNextDay,
                ]);

                if ($flight->apt_transit) {
                    $addInfoLine(FlightLine::class, [
                        'date'         => $reservation->date_depart,
                        'airline'      => $flight->airline->company,
                        'origin'       => $flight->code_apt_transit,
                        'dest'         => $flight->code_apt_arrive,
                        'arr_next_day' => $flight->arrivalNextDay,
                    ]);

                    $addInfoLine(FlightLine::class, [
                        'date'    => $reservation->date_retour,
                        'airline' => $flight->airline->company,
                        'origin'  => $flight->code_apt_arrive,
                        'dest'    => $flight->code_apt_transit,
                    ]);
                }

                $addInfoLine(FlightLine::class, [
                    'airline' => $flight->airline->company,
                    'origin'  => $flight->code_apt_transit ?: $flight->code_apt_arrive,
                    'dest'    => $flight->code_apt_depart,
                ]);
            }

            // CommercialdocHeaderResId

            if ($reservation->transfert) {
                $addInfoLine(HeaderResId::class, ['name' => 'Transfert n°']);

                $addInfoLine(TransferLine::class, [
                    'pickup'   => $reservation->datesHotelStay[0],
                    'dropoff'  => $reservation->datesHotelStay[0],
                    'duration' => '',
                    'route'    => "Aéroport ($flight->code_apt_arrive) - Hotel",
                    'vehicle'  => $reservation->transfert->type,
                ]);
                $addInfoLine(TransferLine::class, [
                    'pickup'   => $reservation->datesHotelStay[1],
                    'dropoff'  => $reservation->datesHotelStay[1],
                    'duration' => '',
                    'route'    => "Hotel - Aéroport ($flight->code_apt_arrive)",
                    'vehicle'  => $reservation->transfert->type,
                ]);
            }

            // = Vol + transfert + Hotel Adulte
            if ($totalsByFlightType = $resTotals->groupBy(fn($tot) => $tot->typePerson['vol'])) {
                $taxesByType = $totalsByFlightType->map(fn($totals) => (object)[
                    'count' => $totals->count(),
                    'sum'   => $totals->sum(fn($tot) => $tot->total['taxes_apt']),
                ])->filter(fn($taxes, $personType) => $taxes->count);

                foreach ($taxesByType as $personType => $taxes) {
                    $items[] = $quote->items()->create([
                        'ord'         => $items_ord++,
                        'description' => "Taxes d'aéroport et surcharge carburant - $personType",
                        'qtty'        => $taxes->count,
                        'unitprice'   => $taxes->sum / $taxes->count,
                        'section'     => 'primary',
                    ]);
                }
            }

            if ($sumVisas = $resTotals->sum(fn($total) => $total->total['visa'])) {
                $count   = $resTotals->filter(fn($total) => $total->total['visa'])->count();
                $items[] = $quote->items()->create([
                    'ord'         => $items_ord++,
                    'description' => "Visa",
                    'qtty'        => $count,
                    'unitprice'   => $sumVisas / $count,
                    'section'     => 'options',
                ]);
            }

            if ($meal ?? false) {
                $qtty  = $resTotals->sum(fn($totals) => ($totals->options['prests'][$meal->id] ?? 0) ? 1 : 0);
                $total = $resTotals->sum(fn($totals) => $totals->options['prests'][$meal->id] ?? 0);

                if ($qtty) {
                    $items[] = $quote->items()->create([
                        'ord'         => $items_ord++,
                        'description' => 'Repas : ' . $meal->type->name,
                        'qtty'        => $qtty,
                        'unitprice'   => $total / $qtty,
                        'section'     => 'options',
                    ]);
                }
            }

            foreach ($reservation->prestations as $prest) {
                if ($meal && $prest->id === $meal->id) continue;
                $qtty  = $resTotals->sum(fn($totals) => (int)isset ($totals->options['prests'][$prest->id]));
                $total = $resTotals->sum(fn($totals) => $totals->options['prests'][$prest->id] ?? 0);
                if ($qtty) {
                    $items[] = $quote->items()->create([
                        'ord'         => $items_ord++,
                        'description' => "Prestation : " . ($prest->name ?: $prest->type->name),
                        'qtty'        => $qtty,
                        'unitprice'   => $total / $qtty,
                        'section'     => 'options',
                    ]);
                }
            }

            foreach ($reservation->tours as $tour) {
                $qtty  = $resTotals->sum(fn($totals) => (int)isset ($totals->options['tours'][$tour->id]));
                $total = $resTotals->sum(fn($totals) => $totals->options['tours'][$tour->id]['total'] ?? 0);
                if ($qtty) {
                    $items[] = $quote->items()->create([
                        'ord'         => $items_ord++,
                        'description' => "Excursion : $tour->nom",
                        'qtty'        => $qtty,
                        'unitprice'   => $total / $qtty,
                        'section'     => 'options',
                    ]);
                }
            }

            $assurances = $reservation->participants
                ->map(fn($p) => $p->assurance)
                ->filter(fn($ass) => $ass)
                ->unique();
            foreach ($assurances as $assurance) {
                $covering = match ($assurance->couverture) {
                    'par personne' => 'individuelle',
                    'par famille'  => 'familiale',
                };
                $duration = match ($assurance->duree) {
                    'annuelle' => 'annuelle',
                    'voyage'   => 'pour le voyage',
                };
                $name     = "$assurance->titre_assurance, $covering, $duration";

                $purchases = $resTotals
                    ->filter(fn($totals) => ($totals->assurance['id'] ?? 0) === $assurance->id)
                    ->map(fn($totals) => $totals->assurance['price']);

                $items[] = $quote->items()->create([
                    'ord'         => $items_ord++,
                    'description' => "Assurance : $name",
                    'qtty'        => $qtty = $purchases->count(),
                    'unitprice'   => $purchases->sum() / $qtty,
                    'section'     => 'options',
                ]);
            }

            return $quote;
        });
    }
}
