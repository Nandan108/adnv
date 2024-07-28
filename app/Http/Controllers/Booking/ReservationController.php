<?php

namespace App\Http\Controllers\Booking;

use App\Models\Accessibilite;
use App\Models\Assurance;
use App\Models\Chambre;
use App\Models\Prestation;
use App\Models\Recommandations;
use App\Models\Reservation;
use App\Models\Vol;
use App\Services\HCaptchaService;
use App\Utils\URL;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Models\Commercialdoc\Quote;

class ReservationController
{
    public function index()
    {
        return inertia(
            'Index/Index',
            [
                'message' => "Hellow from " . __FUNCTION__
            ],
        );
    }

    public function initializeReservation(Request $request)
    {
        $reservData = json_decode($request->get('data'));

        if (!$reservData) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => ['Aucune donné fournie ou données invalides.'],
            ], 400);
        }

        $getId = function ($choiceName) use ($reservData) {
            return (($reservData->choices->$choiceName?->choices[0]) ?? null)?->id;
        };

        $getOptions = function ($choiceName) use ($reservData) {
            return collect($reservData->choices->$choiceName?->choices ?? [])
                ->mapWithKeys(
                    fn($choice) =>
                    // The option must be in this format: [int $id => array $attributes]
                    [$choice->id => (array)$choice->personCounts]
                );
        };

        $repas       = $getOptions('repas');
        $prestations = $getOptions('prestation')->union($repas);

        $reservation = Reservation::getNewInstance(
            // destination et dates
            codePays: $reservData->codePays,
            datesVoyage: $reservData->datesVoyage,
            // voyageurs
            personCounts: (array)$reservData->personCounts,
            agesEnfants: $reservData->agesEnfants,
            // choix du vol, du transfert, de la chambre d'hotel
            chambre_id: $getId('chambre'),
            transfert_id: $getId('transfert'),
            prix_vol_id: $getId('vol'),
            // choix des options de prestation(s), repas et tour(s),
            prestations: $prestations,
            tours: $getOptions('tour'),
        );

        // $totals = $reservation->getTotals();
        $reservation->save();

        // header('Content-type: application/json; charset=utf-8');
        // header('HTTP/1.1 201 Created');

        // die(json_encode($totals));

        return redirect()->route('reservation.show', $reservation);

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Data received successfully',
        //     // 'data' => $data,
        // ], 200);

        // URL::get("reservation.php")->setParams([
        //     'xx'     => MD5($reservation->id),
        //     'mobile' => useragentIsMobile(),
        // ])->redirect();
    }

    public function legacyShow(Reservation $reservation)
    {
        return redirect("/reservation.php/?rID=" . $reservation->hashid());
    }

    public function show(string $reservation, Request $request)
    {
        Debugbar::startMeasure('prepare_reservation', "Full ReservationController.show()");

        Debugbar::startMeasure('loading Reservation', "Loading reservation record");
        $id          = (new Reservation)->hashidToId($reservation);
        $reservation = Reservation::findOrFail($id);
        Debugbar::stopMeasure('loading Reservation');

        Debugbar::startMeasure('loading Reservation', 'loading $reservation->code_pays');
        $codePays = $reservation->code_pays;
        Debugbar::stopMeasure('loading Reservation');

        Debugbar::startMeasure('loading Reservation', 'loading $reservation->personCounts');
        $personCounts = $reservation->personCounts;
        Debugbar::stopMeasure('loading Reservation');

        Debugbar::startMeasure('loading Reservation', 'loading $reservation->chambre?->hotel');
        $hotel = $reservation->chambre?->hotel;
        Debugbar::stopMeasure('loading Reservation');

        $legacyBackUrl = $hotel?->id
            ? '/hotel_detail.php?' . http_build_query([
                'h'          => $hotel?->id,
                'du'         => $reservation->date_depart,
                'au'         => $reservation->date_retour,
                'nb_adultes' => $personCounts['adulte'],
                'ages'       => $reservation->ages_enfants,
                'nb_bebe'    => $personCounts['bebe'],
            ])
            : '/hotels.php?' . http_build_query([
                'destination' => $codePays,
                'du'          => $reservation->date_depart,
                'au'          => $reservation->date_retour,
                'adulte'      => $personCounts['adulte'],
                'ages'        => $reservation->ages_enfants,
                'bebe'        => $personCounts['bebe'],
            ]);

        Debugbar::startMeasure('prepare-data', "Getting insurances");
        $assurances = Assurance::toutesParPrix(
            titreSansAssurance: 'Aucune - je ne désire pas souscrire à une assurance voyage.',
        )->all();
        Debugbar::stopMeasure('prepare-data');

        Debugbar::startMeasure('prepare-data', "Getting countries");
        // get countries
        $listePays = \App\Models\Pays::query()
            // with specific countries first in specified order :
            ->orderByRaw('IFNULL(NULLIF(FIELD(nom_fr_fr, "Suisse", "France", "Espagne", "Portugal"), 0), 1000), nom_fr_fr')
            ->get(['code', 'nom_fr_fr']);
        Debugbar::stopMeasure('prepare-data');

        // $prixTours   = $tours->map(fn(Tour $tour) => $tour->getPrixTour($personCounts));
        Debugbar::startMeasure('prepare-data', "Preparing totals");
        $totals = $reservation->getTotals();
        Debugbar::stopMeasure('prepare-data');

        Debugbar::startMeasure('prepare-data', "Preparing participant info");
        $personLabels = Reservation::PERSON_LABELS;
        //$participants = $reservation->
        $participants = $reservation->getAllParticipants();
        $participants->transform(fn($p, $idx) => (object)[
            ...$p->unsetRelations()->toArray(),
            'typePerson' => $totals[$idx]->typePerson['vol'] ?? $totals[$idx]->typePerson['chambre'],
            'totals'     => $totals[$idx],
            'label'      => $personLabels[$typePrestation = $p->adulte ? 'adulte' : 'enfant'] . ' ' . ($p->idx + 1),
            'kebabLabel' => "$typePrestation-$p->idx",
            'age'        => $p->adulte ? null : $p->getAgeAtDate($reservation->date_depart),
            'minMaxAge'  => $p->adulte ? null : (($age = $p->getAgeAtDate($reservation->date_depart)) < 2 ? [0, 1] : [$age]),
            'minMaxBd'   => $p->adulte ? null : $p->BirthdateMinMax,
        ]);
        Debugbar::stopMeasure('prepare-data');

        Debugbar::startMeasure('prepare-data', "Preparing repas and prestations");
        $prestEstRepas = fn($trueOrFalse) => fn(Prestation $p) => $trueOrFalse == $p->type?->is_meal;
        $repas         = $reservation->prestations->filter($prestEstRepas(true))->first();
        $prestations   = $reservation->prestations->filter($prestEstRepas(false))->keyBy('id');
        Debugbar::stopMeasure('prepare-data');

        Debugbar::stopMeasure('prepare_reservation');

        Debugbar::startMeasure("prepare_reservation_data", "Preparing packet for Inertia");
        $data = [
            'md5Id'           => $reservation->md5Id,
            'reservationHash' => $reservation->hashId,
            'url'             => $legacyBackUrl,
            'hotel'           => $hotel,
            'hotelNights'     => $reservation->nbNuitsHotel,
            'date_depart'     => $reservation->date_depart,
            'date_retour'     => $reservation->date_retour,
            'hashId'          => $reservation->hashId(),
            //'chambre'         => $reservation->chambre,
            'transfert'       => $reservation->transfert,
            'volPrix'         => $reservation->volPrix,
            'tours'           => $reservation->tours->keyBy('id'),
            'repas'           => $repas,
            'prestations'     => $prestations,
            'totals'          => $totals,
            'titres'          => ['Mr' => 'Monsieur', 'Mme' => 'Madame'],
            'listePays'       => $listePays,
            'assurances'      => $assurances,
            'personLabels'    => $personLabels,
            'participants'    => $participants,
            'legacyBackUrl'   => $legacyBackUrl,
            'hcaptchaSitekey' => env('HCAPTCHA_SITEKEY'),
        ];
        Debugbar::stopMeasure("prepare_reservation_data");

        return inertia('Booking/Reservation', $data);
    }


    public function hotelDetail($hotel_id, Request $request)
    {
        //$hotel_id       = $request->input('w') ?? $request->input('h') ?? null;

        if ($destination = $request->input('destination') ?? false) {
            // ancien format
            $tab         = explode("?", $request->input('destination'));
            $destination = $tab[0];
            $date_depart = getISODate($tab[1]);
            $date_retour = getISODate($tab[2]);
            //$nb_adultes     = str_replace('adulte=', '', $tab[3]);
            //$nb_enfants     = str_replace('enfant=', '', $tab[4]);
            $ages[] = (int)str_replace('enfant1=', '', $tab[5]);
            $ages[] = (int)str_replace('enfant=', '', $tab[6]);
            //$nb_bebes       = str_replace('bebe=', '', $tab[7]);
            $ages = array_filter($ages);
            sort($ages);
            $personCounts = collect([
                'adulte' => (int)str_replace('adulte=', '', $tab[3]),
                'enfant' => count($ages),
                'bebe'   => (int)str_replace('bebe=', '', $tab[7]),
            ]);
        } else {
            $date_depart = getISODate($request->input('du'));
            $date_retour = $dai = getISODate($request->input('au'));
            // $nb_adultes   = (int) ($request->input('nb_adultes') ?? 0 ?: $request->input('adulte') ?: 0);
            // $nb_enfants   = (int) ($request->input('nb_enfants') ?? 0 ?: $request->input('enfant') ?: 0);
            // $nb_bebes     = (int) ($request->input('nb_bebes') ?? 0 ?: $request->input('bebe') ?: 0);
            $ages = array_filter(is_array($ages = $request->input('ages') ?? []) ? $ages : explode(',', $ages));
            sort($ages);
            $personCounts = collect([
                'adulte' => (int)($request->input('nb_adultes') ?? 0 ?: $request->input('adulte') ?? 0),
                'enfant' => count($ages),
                'bebe'   => (int)($request->input('nb_bebes') ?? 0 ?: $request->input('bebe') ?? 0),
            ]);
        }

        /* TODO: $tempReservation = new Reservation([
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
            'du'   => $date_depart,
            'au'   => $date_retour,
            ...(array)$personCounts,
            'ages' => $ages,
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
            ->agesValid($ages)
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
                personCounts: $personCounts,
                agesEnfants: $ages,
                datesStay: $datesHotel,
                prixParNuit: true,
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

        // MARK: Tabs
        /** @var Collection<string, StdClass */
        $tabs = collect([
            [
                'nom'         => 'vol',
                'displayName' => 'vol',
                'a'           => 'un',
                'label'       => 'Vol',
                'count'       => count($infoVols),
                'file'        => 'vol.php',
                'icon'        => 'plane',
                'titre'       => 'vol|vols',
            ],
            [
                'nom'         => 'chambre',
                'displayName' => 'chambre',
                'a'           => 'une',
                'label'       => 'Chambre',
                'count'       => count($chambresParHotel),
                'file'        => 'chambre.php',
                'icon'        => 'bed',
                'titre'       => 'chambre|chambres',
                'dataSource'  => 'listeChambres',
            ],
            [
                'nom'            => 'repas',
                'displayName'    => 'repas',
                'a'              => 'un',
                'label'          => 'Repas',
                'count'          => count($infoRepas),
                'file'           => 'repas.php',
                'icon'           => 'glass',
                'titre'          => 'repas|repas',
                'maxChoices'     => 1,
                'obligatoires'   => $infoRepas
                    ->filter(fn($r)   => $r->obligatoire)
                    ->map(fn($r)   => $r->id),
                'requiresChoice' => 'chambre',
                'dataSource'     => 'prixRepas',
            ],
            [
                // TODO:
                'nom'            => 'prestation',
                'displayName'    => 'prestation',
                'a'              => 'une',
                'label'          => 'Prestation',
                'count'          => count($infoPrestations),
                'file'           => 'prestation.php',
                'icon'           => 'chain',
                'titre'          => 'prestation|prestations',
                'requiresChoice' => 'chambre',
                'maxChoices'     => 99,
                'chooseNext'     => false,
                'dataSource'     => 'prixPrestations',
            ],
            [
                'nom'         => 'transfert',
                'displayName' => 'transfert',
                'a'           => 'un',
                'label'       => 'Transfert',
                'count'       => count($prixTransferts),
                'file'        => 'transfert.php',
                'icon'        => 'car',
                'titre'       => 'transfert|transferts',
            ],
            [
                'nom'         => 'tour',
                'displayName' => 'tour/excursion',
                'a'           => 'un',
                'label'       => 'Tour',
                'count'       => count($prixTours),
                'file'        => 'tours.php',
                'icon'        => 'bookmark',
                'titre'       => 'tour|tours',
                // TODO: Question: pourquoi seulement 2 ?
                // Ca devrait dépendre de la durée du séjour, non ?
                // Réponse probable: pour pas se compliquer la vie (code bordelique).
                // Nombre max d'excursions possible: min(5, $nbNuits - 2)
                'maxChoices'  => $maxTourChoice = min(5, $nbNuits - 2),
            ],
        ])->map(fn($tab) => (object)$tab)
            ->filter(fn($tab) => $tab->count)->values()
            ->each(function ($tab, $idx) {
                $tab->idx = $idx;
                // the active tab is the first tab shown (vol if any, otherwise room).
                $tab->active = !$idx;
            })
            ->keyBy('nom');

        // DEBUG: show tours first
        //if ($_ENV['APP_DEBUG']) $tabs = $tabs->sortBy(fn($tab) => $tab->nom !== 'tour');

        return inertia(
            'Booking/HotelDetail',
            [
                'pays'               => $pays,
                'personLabels'       => Reservation::PERSON_LABELS,
                'personCounts'       => $personCounts,
                'datesVoyage'        => $datesVoyage,
                'datesHotel'         => $datesHotel,
                'nbNuits'            => $nbNuits,
                'tabs'               => $tabs,
                'hotel'              => $hotel->withoutRelations(),
                'agesEnfants'        => $ages,
                'listeChambres'      => $listeChambres,
                'prixRepas'          => $infoRepas->values(),
                'infoVols'           => $infoVols,
                'visas'              => $visas,
                'classesReserv'      => Vol::CLASS_RESERVATION,
                'prixPrestations'    => $infoPrestations->values(),
                'aeroports'          => $aeroports,
                'lieuxApt'           => $lieuxApt,
                'prixTransferts'     => $prixTransferts,
                'prixTours'          => $prixTours,
                'allRecommandations' => Recommandations::all()->pluck('description', 'id'),
                'allAccessibilites'  => Accessibilite::all()->pluck('description', 'code'),
            ],
        );
    }

    public function updateTraveler(Reservation $reservation, string $travelerIdx, Request $request)
    {
        if ($p = $reservation->getAllParticipants($travelerIdx)->first()) {
            if ($data = $request->json()->all()) {
                $p->fill($data);
                // $p->id_assurance = $p->id_assurance ?: null;

                $reservation->participants()->save($p);
            }

            $participant = (object)[
                ...$p->unsetRelations()->toArray(),
                'totals'     => $reservation->getTotals($travelerIdx),
                'age'        => $p->adulte ? null : $p->getAgeAtDate($reservation->date_depart),
            ];

            return response()->json($participant);
        }
    }

    // voir /var/www/myprivateboutique.ch/adnv/booking/Devis_paiement_Facture.php
    public function confirmReservation(Reservation $reservation, Request $request, HCaptchaService $captchaService)
    {
        if ($quote = $reservation->load('quote')->quote) {
            return redirect()->route("reservation.quote.show", $quote);
        }

        if (!$captchaService->verify($request->input('captchaToken'))) {
            return back()->withErrors(['captcha' => 'CAPTCHA verification failed.']);
        }

        $clientInfo = $request->validate([
            'remarques'    => 'string|max:1024',
            'lastname'     => "required|string|max:45",
            'firstname'    => "required|string|max:30",
            'email'        => "required|string|max:30",
            'phone'        => "required|string|max:30",
            'street'       => "required|string|max:200",
            'street_num'   => "nullable|string|max:5",
            'zip'          => "required|string|max:10",
            'city'         => "required|string|max:40",
            'country_code' => "required|string|max:2",
        ]);

        $quote = Quote::makeFromTripReservation($reservation, $clientInfo);

        return redirect()->route("reservation.quote.show", $quote);
    }

    public function showQuote(Quote $quote)
    {

        return inertia(
            'Booking/InitialQuote',
            [
                'quote'   => $quote,
                // 'headerInfo' => $quote->headerLines,
                'message' => <<<EOF
                EOF,
            ],
        );
    }
}
