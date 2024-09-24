<?php

namespace App\Services;

use App\Models\Accessibilite;
use App\Models\Chambre;
use App\Models\Prestation;
use App\Models\Recommandations;
use App\Models\Reservation;
use App\Models\Vol;
use App\Utils\URL;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class HotelService
{
    public function getHotelDetailInfo($hotel_id, $tripDates, Collection $personCounts, $agesEnfants = [])
    {
        // TODO: simplify data gathering code by using FrontEndHelperService::extractNormalizedRelations(). Maybe?

        /* TODO: use Reservation to get pricings for hotel_detail page
         * $tempReservation = new Reservation([
         *  'personCounts' => $personCounts,
         *  'hotel_id' => $hotel_id,
         *   ...
         * ]);
         * // then get prices with
         * $participants = $tmpReservations->getAllParticipants();
         * $pricingPersonCounts = $chambre->getPersonCountsForPricing($participants)
         * $chambre->getPrixNuit(personCounts: $pricingPersonCounts, ...)
         * // even better change it to getPrixNuit(Collection $voyageurs, ...) and use getPersonCountsForPricing() in it.
         */

        $url = http_build_query([
            'du'   => $date_depart = $tripDates[0],
            'au'   => $date_retour = $tripDates[1],
            ...(array)$personCounts,
            'ages' => $agesEnfants,
        ]);

        // $adulte = $nb_adultes; $enfant = $nb_enfants; $bebe = $nb_bebes;
        // MARK: DB QUERY
        /** @var \App\Models\Hotel */
        $hotel = \App\Models\Hotel::query()
            ->select([
                ...['id', 'id_lieu', 'nom', 'etoiles', 'situation', 'photo', 'repas', 'coup_coeur', 'decouvrir', 'slug', 'adresse'],
                //...['age_minimum',] // TODO: Check if this field is being used.
                /*,'postal_code','tel','mail',*/
            ])
            ->agesValid($agesEnfants)
            // get all rooms (valid according to arrival date)
            ->withWhereHas(
                'chambres',
                fn(Builder $q) => $q->valid($date_depart, $date_retour, $personCounts)
                    ->select([
                        ...['id_hotel', 'id_chambre', 'nom_chambre', 'photo_chambre', 'monnaie', 'taux_commission', '_villa'],
                        ...['debut_validite', 'fin_validite'],
                        ...['_adulte_1_net', '_adulte_2_net', '_adulte_3_net', '_adulte_4_net'],
                        ...['_enfant_1_net', '_enfant_2_net', '_age_max_petit_enfant'],
                        ...['_bebe_1_net'],
                        ...['remise', 'debut_remise_voyage', 'fin_remise_voyage'],
                        ...['remise2', 'debut_remise2_voyage', 'fin_remise2_voyage'],
                    ])
            )
            // get all meal and other services
            ->with([
                'prestationsAutres' => fn($t) => $t->validPrestation($date_depart, $date_retour)
                    ->orderBy('obligatoire', 'desc')
                    ->orderBy('adulte_net', 'desc'),
                'prestationsRepas'  => fn($t)  => $t->validRepas($date_depart)
                    ->orderBy('obligatoire', 'desc')
                    ->orderBy('adulte_net', 'desc')
            ])
            // get all tours happening in same Ville
            ->with('lieu.memeVilleLieux', fn($lieu) => $lieu
                ->withWhereHas('tours', fn($t) => $t->with('partenaire')->valid($date_depart)))
            // get all transferts to this hotel
            ->with([
                'transferts' => fn($t) => $t->valid($date_depart)
                    ->with(['aeroport', 'partenaire']),
                // get all flights arriving in same region
                'lieu.paysObj',
                'lieu.memeRegionLieux.aeroports',
            ])
            ->find($hotel_id);

        if (!$hotel) {
            include '404.php';
            if (config('app.debug')) {
                $toDump = [
                    // 'pageData'  => json_encode($pageData ?? null, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    // 'Query log' => getQueryLog(),
                ];
                if ($toDump) debug_dump($toDump);
            }
            return;
        }

        $_page_subtitle = "RESERVATION : $hotel->nom";
        // get all airports in the same region as the hotel
        $regionAeroports = $hotel->lieu->memeRegionLieux->flatMap(fn($lieu) => $lieu->aeroports);
        // get all airports that connect to the hotel via a transfert
        $transfertAeroports = $hotel->transferts->map(fn($transfert) => $transfert->aeroport);
        // UNION the two lists of airports defined above
        $aeroports = $transfertAeroports->union($regionAeroports)->unique('id_aeroport');

        // get all valid Commercial flights that arrive at one of the airports in the UNION list
        $aeroports->load([
            'vols_arrive' => fn($query) => $query
                ->validDatePeriod($date_depart)
                ->flightType(Vol::FLIGHT_TYPE_COMMERCIAL)
                ->with([
                    'prix',
                    'airline',
                    'apt_depart'  => fn($q)  =>
                        $q->select(['code_aeroport', 'aeroport', 'id_lieu'])->with('lieu'),
                    'apt_transit' => fn($q) =>
                        $q->select(['code_aeroport', 'aeroport', 'id_lieu'])->with('lieu'),
                    'apt_arrive'  => fn($q)  =>
                        $q->select(['code_aeroport', 'aeroport', 'id_lieu'])->with('lieu'),
                ])
        ]);
        $vols = $aeroports->flatMap(fn($apt) => $apt->vols_arrive);

        $codes_apt_arrive = $hotel->transferts->pluck('dpt_code_aeroport')->unique();

        $datesVoyage = [$date_depart, $date_retour];
        // TODO: $datesHotel[0] -= least($vols->arrive_next_day)
        // TODO: datesHotel should be a front-end computed()
        $datesHotel = [$date_depart, $date_retour];

        $listeChambres = $hotel->chambres->map(
            fn(Chambre $chambre) => $chambre->getPrixNuit(
                tarifPersonCounts: $personCounts,
                agesEnfants: $agesEnfants,
                stayDates: $datesHotel,
                getPerNight: true,
            )
        )->keyBy('id');

        $infoPrestations = $hotel->prestationsAutres
            ->map(
                fn(Prestation $r) => $r->getInfo(
                    personCounts: $personCounts,
                    datesVoyage: $datesHotel,
                )
            )->sortBy('total');

        $infoRepas = $hotel->prestationsRepas
            ->map(
                fn(Prestation $r) => $r->getInfo(
                    personCounts: $personCounts,
                    datesVoyage: $datesHotel,
                )
            )->sortBy('total');

        $mandatoryMealIds    = $infoRepas->filter(fn($r) => $r->obligatoire)->map(fn($r) => $r->id);
        $mandatoryServiceIds = $infoPrestations->filter(fn($r) => $r->obligatoire)->map(fn($r) => $r->id);

        // die(dd(json_encode($infoRepas, JSON_PRETTY_PRINT)));

        // $infoRepas = $allPrestations->filter(fn($prestInfo) => $prestInfo->prestation->type->is_meal);
        // $infoPrestations = $allPrestations->filter(fn($prestInfo) => !$prestInfo->prestation->type->is_meal);

        $prixTransferts = $hotel->transferts
            // TODO: Dynamically hide (un-select if needed) uncompatible transferts
            // after a flight is chosen
            ->map(fn($t) => $t->getPrixTransfert($personCounts, $hotel->nom));

        //$lieuxAeroports = $hotel->lieu->memeRegionLieux->withoutRelations();
        $aeroports = $vols
            ->flatMap(fn($vol) => array_filter([
                $vol->apt_depart,
                $vol->apt_transit,
                $vol->apt_arrive,
            ]));
        $lieuxApt  = $aeroports->map(fn($apt) => $apt->lieu)->keyBy('id_lieu');
        $aeroports = $aeroports->keyBy('code_aeroport')
            ->each(function ($apt) {
                $apt->ville     = $apt->lieu->ville;
                $apt->full_name = "$apt->code_aeroport / {$apt->lieu->ville} ({$apt->aeroport})";
                $apt->unsetRelation('lieu');
            });

        $infoVols = $vols->flatMap(
            fn($vol) =>
            $vol->prix->map(function ($prix) use ($vol, $personCounts, $datesVoyage) {
                $infoVols = $vol->getInfoVol($personCounts, $prix, $datesVoyage[0]);
                $url = URL::get()->setRelative();
                foreach ($infoVols->datesDeparts as $diff => $dd) {
                    if ($diff) {
                        $url->setParams([
                            'du' => $dd->date,
                            'au' => fmtDate('y-MM-dd', dateAddDays($datesVoyage[1], $diff)),
                        ]);
                        $infoVols->datesDeparts[$diff]->url = "$url";
                    }
                }
                return $infoVols;
            })
        )->sort(
                fn($vA, $vB) =>
                empty ($vA->datesDeparts[0]) <=> empty ($vB->datesDeparts[0]) ?:
                $vA->total <=> $vB->total
            )->values();

        $prixTours = $hotel->lieu->memeVilleLieux
            ->flatMap(fn($lieu) => $lieu->tours->map(fn($tour) => $tour->getPrixTour($personCounts)))
            ->sortBy('total')
            ->values();

        $chambresParHotel = $hotel->chambres;
        $ville            = $hotel->lieu->ville;
        $pays             = $hotel->lieu->paysObj;

        $visas = [
            'prix'        => [
                'adulte' => $pays->visa_adulte,
                'enfant' => $pays->visa_enfant,
                'bebe'   => $pays->visa_bebe,
            ],
            'totals'      => $visaTotals = [
                'adulte' => $pays->visa_adulte * $personCounts['adulte'],
                'enfant' => $pays->visa_enfant * $personCounts['enfant'],
                'bebe'   => $pays->visa_bebe * $personCounts['bebe'],
            ],
            'obligatoire' => !!($pays->visa_adulte + $pays->visa_enfant + $pays->visa_bebe), // TODO: remove field
            'total'       => array_sum($visaTotals),
        ];

        $nbNuits = count($listeChambres) ? $listeChambres->first()->nbNuits : 0;

        $info = [
            'personLabels'        => Reservation::PERSON_LABELS,
            'classesReserv'       => Vol::CLASS_RESERVATION,
            'pays'                => $pays,
            'personCounts'        => $personCounts,
            'datesVoyage'         => $datesVoyage,
            'datesHotel'          => $datesHotel,
            'nbNuits'             => $nbNuits,
            //'tabs'               => $tabs,
            'hotel'               => $hotel->withoutRelations(),
            'agesEnfants'         => $agesEnfants,
            'listeChambres'       => $listeChambres,
            'prixRepas'           => $infoRepas->values(),
            'mandatoryServiceIds' => $mandatoryServiceIds,
            'mandatoryMealIds'    => $mandatoryMealIds,
            'infoVols'            => $infoVols,
            'visas'               => $visas,
            'prixPrestations'     => $infoPrestations->values(),
            'aeroports'           => $aeroports,
            'lieuxApt'            => $lieuxApt,
            'prixTransferts'      => $prixTransferts,
            'prixTours'           => $prixTours,
            'allRecommandations'  => Recommandations::all()->pluck('description', 'id'),
            'allAccessibilites'   => Accessibilite::all()->pluck('description', 'code'),
        ];

        return $info;
    }
}