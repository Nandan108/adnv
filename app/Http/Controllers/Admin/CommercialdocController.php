<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NewQuoteNotificationMail;
use App\Models\Aeroport;
use App\Models\Airline;
use App\Models\Commercialdoc;
use App\Models\Commercialdoc\Invoice;
use App\Models\Commercialdoc\Quote;
use App\Models\Pays;
use App\Services\CommercialdocService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CommercialdocController extends Controller
{

    public function __construct(
        private CommercialdocService $docService,
    ) {}

    protected function allRelations()
    {
        return [
            'items',
            'infos',
            'country',
            'reservation' => fn($q) => $q->with([
                'chambre.hotel.lieu.paysObj',
                'chambre.monnaieObj',
                'prixVol.vol.airline',
                'prixVol.vol.monnaieObj',
                'transfert',
                'prestations',
                'tours.monnaieObj',
                'participants.assurance',
            ])
        ];
    }

    public function index()
    {
        $quotes = Commercialdoc::with($this->allRelations())->get();

        // append add personCounts attribute to reservations
        $quotes->map(fn($q) => $q->reservation)
            ->append(['url', 'personCounts']);

        // Extract normalized collections of relations (using a helper macro)
        $normalizedData = $quotes->extractNormalizedRelationsForFrontEnd();

        return inertia('Admin/Booking/Index', [
            'data' => $normalizedData,
        ]);
    }

    // route: admin.quote.edit
    public function edit(string $quote)
    {
        $quoteId = (new Quote)->hashidToId($quote);
        /** @var Quote $quote */
        $quote = Quote::with($this->allRelations())->findOrFail($quoteId);

        // Check for the presence of final items.
        // If none are found, create them by copying the initial ones.
        $finalItems = $quote->items->where('stage', 'final');
        if ($finalItems->isEmpty()) {
            foreach ($quote->items->where('stage', 'initial') as $initialItem) {
                $finalItem                   = $initialItem->replicate();  // Copy the initial item
                $finalItem->stage            = 'final';  // Set the stage to 'final'
                $finalItem->commercialdoc_id = $quote->id;  // Ensure it's attached to the quote
                $finalItem->save();  // Save the final item
            }
        }

        // append add personCounts attribute to reservations
        $quote->reservation->append('personCounts');

        $data            = collect([$quote])->extractNormalizedRelationsForFrontEnd();
        $data['Airport'] = Aeroport::get();
        $data['Airline'] = Airline::get();

        return inertia('Admin/Booking/EditQuote', [
            'data'    => $data,
            'quoteId' => $quote->id,
        ]);
    }

    public function updateQuote(Request $request, Quote $quote)
    {
        // TODO: validate input
        // TODO: $quote->update($validatedInput);
        // redirect to route(admin.quote.show)
    }

    public function submitFinalQuote(Request $request, Quote $quote)
    {
        $this->docService->sendFinalQuoteToClient($quote);
        return response()->json(['message' => 'Final quote sent successfully.']);
    }

    // TODO: move this function should go to the client frontend Booking controller
    public function clientValidates(Quote $quote, Request $request)
    {
        $this->docService->clientValidatesQuote($quote);
        // Show message "Votre facture vous sera soumise d'ici peu.
        // return inertia('quote/show', []);
    }

    public function previewInvoice(Quote $document)
    {
        // TODO: show unconfirmed invoice, with field to enter deposit amount
        // reuse client-side invoice component
        $document->load('events');
        $document->type = 'invoice'; // pretend it's an invoice (but don't save)

        return inertia('Admin/Booking/Invoice', [
            //'data' => $extractedRelations,
            'data' => collect([$document])->extractNormalizedRelationsForFrontEnd(),
        ]);
    }

    public function generateAndSendInvoice(Invoice $invoice, Request $request)
    {
        $this->docService->generateAndSendInvoice($invoice, $request->integer('deposit'));
        // reuse client-side invoice component

        return inertia('Admin/Booking/invoice', [
            //'data' => $extractedRelations,
        ]);
    }

    public function enterReceivedPayment(Invoice $invoice, Request $request)
    {
        // $this->docService->enterReceivedPayment($invoice, $amount, $method, $markAsDepositPaid);
    }

    public function mailQuoteLink(Quote $quote)
    {
        $successfullySent = $this->docService->sendInitialQuote($quote, $error);

        return back()->with(
            [
                ...($successfullySent
                    ? ['success' => "Le lien vers le devis n°$quote->doc_id a été envoyé à $quote->email."]
                    : ['error' => "Le mail n'a pas pu être envoyé: $error"]
                ),
            ],
        );
    }

    public function mailFinalQuoteLink(Quote $quote)
    {
        $successfullySent = $this->docService->sendFinalQuoteToClient($quote, $error);

        return redirect()->to(route('admin.reservation.index'))
            ->with(
                [
                    ...($successfullySent
                        ? ['success' => "Le lien vers le devis FINAL n°$quote->doc_id a été envoyé à $quote->email."]
                        : ['error' => "Le mail n'a pas pu être envoyé à $quote->email."]
                    ),
                ],
            );
    }

    // action 1: Client inputs data, system create and send initial quote
    // action 2: admin sends final quote to client
    // action 3: client validates
    // action 4: Quote becomes Invoice, admin inputs deposit amount and sends invoice
    // action 5: Deposit payment confirmed (by manager)


    // status 1: "Initial quote sent" - awaiting creation of final quote by admin
    // status 2: "Final quote sent" - awaiting client validation
    // status 3: "Quote Validated" - awaiting invoice generation
    // status 4: Awaiting deposit payment
    // status 5: Awaiting payment of balance.
    // status 6: Fully paid.
    // status 10: Canceled by client
    // status 11: Canceled by system (due to deadline passed)
    // status 12: Canceled by admin



    public function mailInvoice(Commercialdoc $document, Request $request) {

        try {
            // get deposit amount
            $amount = $request->validate(["depositAmount"=> 'required|integer',])['depositAmount'];

            $this->docService->generateAndSendInvoice($document, $amount);
        } catch (\Illuminate\Validation\ValidationException $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }

        // $this->docService->generateAndSendInvoice($document, $validated['depositAmount']);
        session()->flash('success', 'La facture a été envoyée au client.');

        return redirect()->back();
    }
}
