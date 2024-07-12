<?php

namespace App\Models\Commercialdoc;

use App\Models\Commercialdoc;
use App\Models\Reservation;

class Quote extends Commercialdoc
{

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Append the additional attribute
        $this->appends[] = 'header_specific_lines';
        $this->appends[] = 'travelers';
    }

    public static function makeFromTripReservation(Reservation $reservation, array $clientInfo): self
    {
        $docId = date('dmy');

        $resTotals = $reservation->getTotals();

        // if ($existingCount = Commercialdoc::where('doc_id', 'like', "$docId%")->count()) {
        //     $docId .= $existingCount;
        // };

        $quote = self::create([
            'doc_id'         => $docId,
            'currency_code'  => 'CHF',
            'reservation_id' => $reservation->id,
            'object_type'    => 'trip',
            ...$clientInfo,
        ]);

        // Séjour adultes 13 nuits à l’hôtel Canonnier Beachcomber Golf Resort& Spa 4* | 2 | 4285.00 | 8570.00

        // = Vol + transfert + Hotel Adulte
        $totalsByFlightType = $resTotals->groupBy(fn($tot) => $tot->typePerson['vol']);

        // Let's group non-adults together as 'child'
        $totalsByType = collect([
            'adult'    => $resTotals->filter(fn($tot)    => $tot->typePerson['vol'] === 'adulte'),
            'nonAdult' => $resTotals->filter(fn($tot) => $tot->typePerson['vol'] !== 'adulte'),
            'child'    => $resTotals->filter(fn($tot)    => $tot->typePerson['vol'] === 'enfant'),
            'baby'     => $resTotals->filter(fn($tot)     => $tot->typePerson['vol'] === 'bebe'),
        ]);

        $tripTotalByType = $totalsByType->map(fn($totals) => (object)[
            'count' => $totals->count(),
            'sum'   => $totals->sum(
                fn($tot)   => $tot->total['vol'] + $tot->total['transfert'] + $tot->total['chambre']
            ),
        ]);

        $nomHotel = $reservation->chambre->hotel->nom;

        $items[] = $quote->items()->create([
            'description'  => "Séjour adulte $reservation->nbNuitsHotel nuits à l'hôtel \"$nomHotel\"",
            'qtty'         => $count = $tripTotalByType['adult']->count,
            'unitprice'    => $tripTotalByType['adult']->sum / $count,
            'discount_pct' => 0, // TODO input pre-discount unitprice, then show discount
            'section'      => 'primary',
        ]);

        if (($nonAdult = $tripTotalByType['nonAdult'])->count) {
            $items[] = $quote->items()->create([
                'description'  => "Séjour enfant",
                'qtty'         => $nonAdult->count,
                'unitprice'    => $nonAdult->sum / $nonAdult->count,
                'discount_pct' => 0,
                'section'      => 'primary',
            ]);
        }

        $taxesByType = $totalsByFlightType->map(fn($totals) => (object)[
            'count' => $totals->count(),
            'sum'   => $totals->sum(fn($tot)   => $tot->total['taxes_apt']),
        ])->filter(fn($taxes, $personType) => $taxes->count);

        foreach ($taxesByType as $personType => $taxes) {
            $items[] = $quote->items()->create([
                'description' => "Taxes d'aéroport et surcharge carburant - $personType",
                'qtty'        => $taxes->count,
                'unitprice'   => $taxes->sum / $taxes->count,
                'section'     => 'primary',
            ]);
        }

        if ($sumVisas = $resTotals->sum(fn($total) => $total->total['visa'])) {
            $count   = $resTotals->filter(fn($total) => $total->total['visa'])->count();
            $items[] = $quote->items()->create([
                'description' => "Visa",
                'qtty'        => $count,
                'unitprice'   => $sumVisas / $count,
                'section'     => 'options',
            ]);
        }
        ;

        $meal = $reservation->prestations->
            filter(fn($prest) => $prest->type?->is_meal)->first();
        if ($meal) {
            $qtty  = $resTotals->sum(fn($totals) => ($totals->options['prests'][$meal->id] ?? 0) ? 1 : 0);
            $total = $resTotals->sum(fn($totals) => $totals->options['prests'][$meal->id] ?? 0);

            if ($qtty) {
                $items[] = $quote->items()->create([
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
                'description' => "Assurance : $name",
                'qtty'        => $qtty = $purchases->count(),
                'unitprice'   => $purchases->sum() / $qtty,
                'section'     => 'options',
            ]);
        }

        return $quote;
    }

    public function getHeaderSpecificLinesAttribute()
    {
        return array_filter([
            ['label' => 'Devis n°', 'value' => $this->doc_id],
            ['label' => 'Date', 'value' => $this->created_lcl],
            ['label' => 'Echéance', 'value' => $this->deadline_lcl],
            ['label' => 'Monnaie', 'value' => "{$this->currency->code} ({$this->currency->nom_monnaie})"],
        ], fn($line) => $line['value']);
    }

    public function getTravelersAttribute() {
        return $this->reservation->participants->sortBy('idx')->sortByDesc('adulte')->values();
    }
}
