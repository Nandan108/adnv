<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Mail\NewQuoteNotificationMail;
use App\Models\Assurance;
use App\Models\Commercialdoc;
use App\Models\Commercialdoc\Quote;
use App\Models\Reservation;
use App\Services\CommercialdocService;
use App\Services\HotelService;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class ReservationController extends Controller
{
    public function __construct(
        public CommercialdocService $commercialdocService,
    ) {}

    public function index()
    {
        return Inertia::render(
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

        return redirect()->route('booking.show', $reservation);

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

    public function show(string $booking, Request $request)
    {

        Debugbar::startMeasure('prepare_reservation', "Full ReservationController.show()");

        Debugbar::startMeasure('loading Reservation', "Loading reservation record");
        $id      = (new Reservation)->hashidToId($booking);
        $booking = Reservation::findOrFail($id);

        $booking->load(
            'quote',
            'chambre.hotel.lieu.paysObj',
            'chambre.monnaieObj',
            'prixVol.vol.airline',
            'prixVol.vol.monnaieObj',
            'transfert',
            'prestations',
            'tours.monnaieObj',
        );
        $booking->append('personCounts');

        // TODO: split flight-only reservation into their own reservation system, rather than mix
        // this functionality into hotel reservations, which is senseless complexification.
        $backUrlData = ($booking->chambre
            ? ['h' => $booking->chambre->id_hotel]
            : ['destination' => $booking->code_pays]) + [
            'du'     => $booking->date_depart,
            'au'     => $booking->date_retour,
            'adulte' => $booking->nb_adulte,
            'ages'   => $booking->ages_enfants,
            'bebe'   => $booking->babyCount,
        ];
        $backUrl     = ($booking->chambre ? '/hotel_detail.php?' : '/hotels.php?') . http_build_query($backUrlData);

        // Special treatment for participants, as we want to include yet-unpersisted Voyageur records
        $booking->setRelation('participants', $booking->getAllParticipants());

        // get everything normalized
        $normalizedData = collect([$booking])->extractNormalizedRelationsForFrontEnd();

        Debugbar::startMeasure('prepare-data', "Preparing participant info");
        $totals = $booking->getTotals();
        $normalizedData['Traveler']->transform(fn($t, $idx) => (object)[
            ...$t->toArray(),
            'totals' => $totals[$idx],
        ]);
        Debugbar::stopMeasure('prepare-data');

        Debugbar::startMeasure('prepare-data', "Getting insurances and countries");
        $titreSansAssurance          = 'Aucune - je ne désire pas souscrire à une assurance voyage.';
        $normalizedData['Insurance'] = Assurance::toutesParPrix($titreSansAssurance)->all();
        // get countries
        $normalizedData['Country'] = \App\Models\Pays::query()
            // with specific countries first in specified order :
            ->orderByRaw("FIELD(nom_fr_fr, 'Suisse', 'France', 'Espagne', 'Portugal') DESC, nom_fr_fr ASC")
            ->get(['code', 'nom_fr_fr']);
        Debugbar::stopMeasure('prepare-data');

        return Inertia::render('Booking/Reservation', [
            'normalizedData'  => $normalizedData,
            //'TravelerTotals'  => $totals,
            'titles'          => ['Mr' => 'Monsieur', 'Mme' => 'Madame'],
            'personLabels'    => Reservation::PERSON_LABELS,
            'hotelDetailURL'  => $backUrl,
            'hcaptchaSitekey' => env('HCAPTCHA_SITEKEY'),
        ]);
    }

    /**
     * Extract a date from $date string
     * @param [type] $date
     * @return string date in YYYY-mm-dd format
     */
    private function getISODate($date): string
    {
        if (
            preg_match('/\b((?P<Y>\d{4})-(?P<M>\d\d)-(?P<D>\d\d) |
                     (?P<d>\d\d)-(?P<m>\d\d)-(?P<y>\d{4}))\b/x', $date, $match)
        ) {
            $y = $match['Y'] ?: $match['y'];
            $m = $match['M'] ?: $match['m'];
            $d = $match['D'] ?: $match['d'];
            return "$y-$m-$d";
        }
        return false;
    }

    public function hotelDetail($hotel_id, Request $request, HotelService $hotelService)
    {
        $code_pays    = $request->input('destination');
        $date_depart  = getISODate($request->input('du'));
        $date_retour  = getISODate($request->input('au'));
        $tripDates    = [$date_depart, $date_retour];
        $ages         = array_filter(is_array($ages = $request->input('ages') ?? []) ? $ages : explode(',', $ages));
        $personCounts = collect([
            'adulte' => (int)($request->input('adulte') ?? 0),
            'enfant' => count($ages),
            'bebe'   => (int)($request->input('bebe') ?? 0),
        ]);
        sort($ages);

        $hotelDetailInfo = $hotelService->getHotelDetailInfo($hotel_id, $tripDates, $personCounts, $ages);

        // MARK: Tabs
        /** @var Collection<string, StdClass */
        $tabs = collect([
            [
                'nom'         => 'vol',
                'displayName' => 'vol',
                'a'           => 'un',
                'label'       => 'Vol',
                'count'       => count($hotelDetailInfo['infoVols']),
                'file'        => 'vol.php',
                'icon'        => 'plane',
                'titre'       => 'vol|vols',
            ],
            [
                'nom'         => 'chambre',
                'displayName' => 'chambre',
                'a'           => 'une',
                'label'       => 'Chambre',
                'count'       => count($hotelDetailInfo['chambresParHotel']),
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
                'count'          => count($hotelDetailInfo['prixRepas']),
                'file'           => 'repas.php',
                'icon'           => 'glass',
                'titre'          => 'repas|repas',
                'maxChoices'     => 1,
                'obligatoires'   => $hotelDetailInfo['mandatoryMealIds'],
                'requiresChoice' => 'chambre',
                'dataSource'     => 'prixRepas',
            ],
            [
                // TODO:
                'nom'            => 'prestation',
                'displayName'    => 'prestation',
                'a'              => 'une',
                'label'          => 'Prestation',
                'count'          => count($hotelDetailInfo['prixPrestations']),
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
                'count'       => count($hotelDetailInfo['prixTransferts']),
                'file'        => 'transfert.php',
                'icon'        => 'car',
                'titre'       => 'transfert|transferts',
            ],
            [
                'nom'         => 'tour',
                'displayName' => 'tour/excursion',
                'a'           => 'un',
                'label'       => 'Tour',
                'count'       => count($hotelDetailInfo['prixTours']),
                'file'        => 'tours.php',
                'icon'        => 'bookmark',
                'titre'       => 'tour|tours',
                // TODO: Question: pourquoi seulement 2 ?
                // Ca devrait dépendre de la durée du séjour, non ?
                // Réponse probable: pour pas se compliquer la vie (code bordelique).
                // Nombre max d'excursions possible: min(5, $nbNuits - 2)
                'maxChoices'  => $maxTourChoice = min(5, $hotelDetailInfo['nbNuits'] - 2),
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

        return Inertia::render(
            'Booking/HotelDetail',
            $hotelDetailInfo,
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

            $normalizedData = collect([$p])->extractNormalizedRelationsForFrontEnd();


            $normalizedData['Traveler'][0] = [
                ...$normalizedData['Traveler'][0]->toArray(),
                'booking_type' => 'booking',
                'totals'       => $reservation->getTotals($travelerIdx),
                'age'          => $p->adulte ? null : $p->getAgeAtDate($reservation->date_depart),
            ];

            return response()->json($normalizedData);
        }
    }

    // voir /var/www/myprivateboutique.ch/adnv/booking/Devis_paiement_Facture.php
    public function confirmReservation(
        Reservation $reservation,
        Request $request
    ) {
        if ($quote = $reservation->load('quote')->quote) {
            return redirect()->route("reservation.quote.show", $quote);
        }

        $clientInfo = $request->validate([
            'remarques'    => 'string|max:1024',
            'lastname'     => "required|string|max:45",
            'firstname'    => "required|string|max:30",
            'title'        => "required|string|max:10",
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

    public function mailQuoteLink(Quote $quote)
    {
        $mail = new NewQuoteNotificationMail($quote);

        Mail::to($quote->email)
            ->cc($bcc = env("COMMERCIAL_ADMIN_EMAIL"))
            ->send($mail);
    }

    public function showQuoteMail(Quote $quote)
    {
        $html = (new NewQuoteNotificationMail($quote))->content();
        return response($html->html);
    }

    public function validateQuote(Quote $quote)
    {
        $sentMessage = $this->commercialdocService->clientValidatesQuote($quote);

        // $html = (new ClientNotificationMail("Devis N°$quote->doc_id validé", <<<EOF
        //     {{ $quote->longTitle }} {{ $quote->lastname }},
        //     Nous vous remercions d'avoir validé notre devis n°$quote->doc_id.
        //     Votre facture et autres documents vous seront envoyé sous 3 jours ouvrable.
        // EOF))->build()->buildView()['html'];

        $successError = $sentMessage ? 'success' : 'error';
        $message = $sentMessage ? <<<EOF
                <p>Nous vous remercions d'avoir validé notre devis n°$quote->doc_id.</p>
                <p>Votre facture et autres documents vous seront envoyé sous 3 jours ouvrable.</p>
            EOF :
            "La confirmation de votre validation n'a pas pu vous être envoyé.";

        $flash = [$successError => $message];

        return redirect()->back()->with($successError, $message);
    }

    public function showQuote(Quote $quote)
    {
        $normalizedData = collect([$quote])->extractNormalizedRelationsForFrontEnd();
        return Inertia::render(
            'Booking/FinalQuote',
            [
                'finalQuote' => false,
                'data' => $normalizedData,
                //'data'   => collect([$quote])->extractNormalizedRelationsForFrontEnd(),
                'message' => '',
            ],
        );
    }

    public function showFinalQuote(Quote $quote)
    {
        //$quote->append($quote->getAppends());
        $normalizedData = collect([$quote])->extractNormalizedRelationsForFrontEnd();

        return Inertia::render(
            'Booking/FinalQuote',
            [
                'finalQuote' => true,
                //'quote'  => $quote,
                'data' => $normalizedData,
            ],
        );
    }
    public function showInvoice(Commercialdoc $invoice)
    {
        $invoice->append($invoice->getAppends());
        $normalizedData = collect([$invoice])->extractNormalizedRelationsForFrontEnd();

        return Inertia::render(
            'Booking/Invoice',
            [
                //'quote'  => $quote,
                'data' => $normalizedData,
            ],
        );
    }

}
