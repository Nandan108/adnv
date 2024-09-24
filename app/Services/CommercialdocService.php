<?php

namespace App\Services;

use App\Enums\CommercialdocEventType;
use App\Mail\NewQuoteNotificationMail;
use App\Models\Commercialdoc;
use App\Enums\CommercialdocStatus;
use App\Mail\AdminNotificationMail;
use App\Mail\InvoiceNotificationMail;
use App\Models\Commercialdoc\Quote;
use Illuminate\Mail\SentMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/*
TODO:
- When final quote is sent, there must
- Add button 'VALIDER CE DEVIS" to resources/js/Pages/Booking/FinalQuote.vue
    This button will execute $commercialdocService->clientValidatesQuote($doc);
        Which will send a mail to admin, with link to final-quote document (Invoice Candidate).
        The invoice candidate is the same final quote, shown as an invoice.
// clientValidatesQuote
- in EditQuote.vue, place info sections within an accordion titled "Info for Invoice"
    - Accordion's status is closed by default unless quote has been validated
// generateAndSendInvoice
- In FinalQuote.vue, if status is QUOTE_VALIDATED and user is logged in, show button Edit Quote Info
    - After all the info sections, show total and <input type="number" value="round(total/300)*100">
        If some infos are missing data, show message and disable submit button.
        submit button should trigger generateAndSendInvoice()
// enterReceivedPayment
- if status->isAwaitingPayment() show payment(s) received, balance, [Enter Payment] button in
// cancelByClient
// deadlineExceeded
// cancelByAdmin
*/
class CommercialdocService
{

    protected function requireStatus(Commercialdoc $doc, CommercialdocStatus|array $status)
    {
        if (!collect($status)->flatten()->contains($doc->status)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'status' => ['Cannot send final quote. The document must be in the "Initial Quote Sent" status.'],
            ]);
        }
    }

    public function sendInitialQuote(Commercialdoc $quote, &$errorMessage = null): bool|SentMessage
    {
        $this->requireStatus($quote, [
            CommercialdocStatus::INITIAL_QUOTE_CREATED,
            CommercialdocStatus::INITIAL_QUOTE_SENT,
        ]);

        $mail = new NewQuoteNotificationMail($quote);
        try {
            $sentMessage = Mail::to($quote->email)
                ->cc($bcc = env("COMMERCIAL_ADMIN_EMAIL"))
                ->send($mail);

            $quote->update(["status" => CommercialdocStatus::INITIAL_QUOTE_SENT]);
            $quote->logEvent(CommercialdocEventType::INITIAL_QUOTE_SENT, ['docId' => $quote->docId]);

            return $sentMessage;
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return false;
        }
    }

    public function sendFinalQuoteToClient(Commercialdoc $finalQuote, &$errorMessage = null): bool|SentMessage
    {
        $this->requireStatus($finalQuote, CommercialdocStatus::INITIAL_QUOTE_SENT);

        // Logic for sending final quote
        try {
            $mail        = new NewQuoteNotificationMail($finalQuote);

            $sentMessage = Mail::to($finalQuote->email)
                ->cc($bcc = env("COMMERCIAL_ADMIN_EMAIL"))
                ->send($mail);

            // update status of commercial doc
            $finalQuote->update([
                "status"   => CommercialdocStatus::FINAL_QUOTE_SENT,
                // the client has 3 days to validate
                "deadline" => date('Y-m-d', strtotime('+3 day')),
            ]);

            // record event in log of commercial doc
            $finalQuote->logEvent(CommercialdocEventType::FINAL_QUOTE_SENT);

            return $sentMessage;
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return false;
        }


    }

    public function clientValidatesQuote(Commercialdoc $doc)
    {
        // $this->requireStatus($doc, CommercialdocStatus::FINAL_QUOTE_SENT);

        $finalizeRoute = route('admin.quote.edit', [$doc]);

        // TODO: send mail to admin, with link to form where he can input deposit amount and submit.
        $sentMessage = Mail::to(env("COMMERCIAL_ADMIN_EMAIL"))
            ->send(new AdminNotificationMail(
                subject: "Le devis final $doc->doc_id a été validé!",
                body: <<<EOF
                    Client: $doc->longTitle $doc->lastname $doc->firstname.

                    Vous pouvez dès à présent [button|$finalizeRoute|finalizer la facture].
                EOF,
            ));

        if ($sentMessage) {
            $doc->update(["status" => CommercialdocStatus::QUOTE_VALIDATED]);
            $doc->logEvent(CommercialdocEventType::QUOTE_VALIDATED);
        }

        return $sentMessage;
    }

    public function generateAndSendInvoice(Commercialdoc $doc, int $depositAmount)
    {
        $this->requireStatus($doc, CommercialdocStatus::QUOTE_VALIDATED);

        try {
            DB::transaction(function () use ($doc, $depositAmount) {
                $doc->logEvent(CommercialdocEventType::INVOICE_SENT, [
                    'depositAmount' => $depositAmount,
                ]);

                // Change the document into an invoice
                $doc->update([
                    'type'   => 'invoice',
                    'status' => CommercialdocStatus::AWAITING_DEPOSIT_PAYMENT,
                    'doc_id' => Commercialdoc::getNewDocID(),
                ]);

                // TODO: send invoice to client
                $mail        = new InvoiceNotificationMail($doc);
                $sentMessage = Mail::to($doc->email)
                    ->cc($bcc = env("COMMERCIAL_ADMIN_EMAIL"))
                    ->send($mail);

                // throw new \Exception('DEBUGGING: abort the transaction');
            });
        } catch (\Throwable $e) {
        }

        return $doc;
    }

    public function enterReceivedPayment(Commercialdoc $doc, $amount, $method, $markAsDepositPaid = false)
    {
        $this->requireStatus($doc, [
            CommercialdocStatus::AWAITING_DEPOSIT_PAYMENT,
            CommercialdocStatus::AWAITING_BALANCE_PAYMENT,
        ]);

        // Get amount of previous payments
        $alreadyPaid = $doc->load('events')->events
            ->filter(fn($evt) => $evt->type === 'payment')
            ->map(fn($evt) => $evt->data['amount'])
            ->sum();

        // Save payment event
        $doc->logEvent(CommercialdocEventType::PAYMENT_RECIEVED, [
            'amount'    => $amount,
            'method'    => $method,
            'totalPaid' => $totalPaid = $alreadyPaid + $amount,
        ]);

        // get new status according to sum of payments
        $newStatus = match (true) {
            $totalPaid < $doc->getDepositAmount() => CommercialdocStatus::AWAITING_DEPOSIT_PAYMENT,
            $totalPaid < $doc->getTotalAmount()   => CommercialdocStatus::AWAITING_BALANCE_PAYMENT,
            default                               => CommercialdocStatus::FULLY_PAID,
        };

        if ($newStatus !== $doc->status) {
            // Mail super-admin to inform about change status?
            // Shouldn't be necessary.
            $doc->update(["status" => $newStatus]);
        }
    }

    // TODO: check with Seb to see if we remvoe this action and let quotes just expire.
    public function cancelByClient(Commercialdoc $doc)
    {
        $doc->update(["status" => CommercialdocStatus::CANCELED_BY_CLIENT]);
        $doc->logEvent(CommercialdocEventType::CLIENT_CANCELED);
    }

    // TODO: Check if there are any messages to send or other actions to take in this case
    public function deadlineExceeded(Commercialdoc $doc)
    {
        $doc->update(["status" => CommercialdocStatus::DEADLINE_EXCEEDED]);
        $doc->logEvent(CommercialdocEventType::QUOTE_EXPIRED);

    }

    public function cancelByAdmin(Commercialdoc $doc, string $reason)
    {
        $doc->update([
            "status" => CommercialdocStatus::CANCELED_BY_ADMIN,
            "data"   => ["reason" => $reason],
        ]);
        $doc->logEvent(CommercialdocEventType::ADMIN_CANCELED);
    }
}
