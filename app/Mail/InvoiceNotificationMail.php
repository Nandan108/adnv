<?php

namespace App\Mail;

use App\Models\Commercialdoc;
use App\Models\Commercialdoc\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class InvoiceNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        private Commercialdoc $invoice,
    ) {}

    /**
     * Get the message content definition.
     */
    public function build()
    {
        $subject = 'Votre facture ADN Voyage';
        $content = [
            'title'        => "$subject n°{$this->invoice->doc_id}",
            'customerName' => $this->invoice->longTitle . ' ' . $this->invoice->lastname,
            'messageBody'  => <<<EOF
                Nous vous prions de trouver ci-dessous le lien vers votre facture n°{$this->invoice->doc_id}.
            EOF,
            'action'       => [
                'route'  => 'reservation.invoice.show',
                'params' => $this->invoice,
                'text'   => 'Voir votre facture',
            ],
        ];

        // Render the Blade template into HTML
        $html = view('emails.genericCustomerNotification', $content)->render();

        // Inline the CSS using CssToInlineStyles
        $inliner     = new CssToInlineStyles();
        $inlinedHtml = $inliner->convert($html); //, $css);

        // Return the inlined HTML as the content
        return $this->subject($subject)->html($inlinedHtml);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
