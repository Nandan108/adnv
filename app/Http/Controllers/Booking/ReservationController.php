<?php

namespace App\Http\Controllers\Booking;

use App\Models\Reservation;
use Barryvdh\Debugbar\Facades\Debugbar;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Commercialdoc\Quote;

class ReservationController
{
    public function index()
    {
        Debugbar::alert(['test 2' => time()]);

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

    public function show(Reservation $reservation)
    {
        return redirect("/reservation.php?rID=" . $reservation->hashid());
    }

    public function updateTraveler(Reservation $reservation, string $travelerIdx, Request $request)
    {
        $participants = $reservation->getParticipants();

        [$adulte, $idx] = explode('-', $travelerIdx);
        $participant    = $participants->first(fn($p) => $p->adulte == $adulte && $p->idx == $idx);

        if ($participant) {
            if ($data = $request->json()->all()) {
                $participant->fill($data);
                $participant->id_assurance = $participant->id_assurance ?: null;
                try {
                    // saving from reservation rather than the participant directly
                    // so that the relationship is properly set up.
                    $reservation->push();
                } catch (\Exception $e) {
                }
            }
            $participant->totals = $reservation->getTotals($participant);

            return response()->json($participant);
        }
    }

    // voir /var/www/myprivateboutique.ch/adnv/booking/Devis_paiement_Facture.php
    public function confirmReservation(Reservation $reservation, Request $request)
    {
        if (!CaptchaController::staticCheck($request->input('captchaUserInput') ?? '', $response)) {
            return response(null, 422);
        }

        $validator = Validator::make($requestData = $request->all(), [
            'remarques'        => 'string|max:1024',
            'lastname'         => "required|string|max:45",
            'firstname'        => "required|string|max:30",
            'email'            => "required|string|max:30",
            'phone'            => "required|string|max:30",
            'street'           => "required|string|max:200",
            'street_num'       => "nullable|string|max:5",
            'zip'              => "required|string|max:10",
            'city'             => "required|string|max:40",
            'country_code'     => "required|string|max:2",
            'cgcv'             => "required|numeric",
            'document-valid'   => "required|numeric",
        ]);

        if ($validator->fails()) {
            return response(<<<EOF
                <!DOCTYPE html><html lang="en"><head>
                    <meta http-equiv="refresh" content="0; url=javascript:history.back();">
                </head><body></body></html>
            EOF);

            // Return error response if validation fails
            // return response()->json([
            //     'success' => false,
            //     'message' => 'Validation error',
            //     'errors'  => $validator->errors(),
            // ], 422);
        }

        // $reservation->fill($requestData)->save();

        $quote = Quote::makeFromTripReservation($reservation, $requestData);


        return redirect()->route("reservation.quote.show", $quote);

        // Create devis object

        //Show message
        // Nous accusons réception de votre demande, qui va être traitée dans les plus brefs délais, selon les ouvertures de notre agence.<br>
        // <br>
        // Votre devis final vous sera transmit par courriel. En cas de non-réponse de notre part dans les 24 heures (jours ouvrés) et après avoir vérifié votre boîte de spam, nous vous prions de nous addresser un courriel afin de vérifier notre envoi.<br>
        // <br>
        // En vous remerciant pour votre compréhension et au nom d\'ADN voyage, nous vous souhaitons une bonne journée ou soirée.<br>


        // Devis.id_reservation,


        // Next is Devis_paiement_Facture.php
    }

public function showQuote(Quote $quote) {

        return inertia(
            'Booking/InitialQuote',
            [
                'quote' => $quote,
                // 'headerInfo' => $quote->headerLines,
                'message' => <<<EOF
                EOF,
            ],
        );
    }
}
