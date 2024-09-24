<?php

namespace App\Mail;

use App\Models\Commercialdoc\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class NewQuoteNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        private Quote $quote,
    ) {}

    /**
     * Get the message content definition.
     */
    public function build()
    {
        // Render the Blade template into HTML
        $html = view('emails.quoteNotification', ['quote' => $this->quote])->render();

        // Inline the CSS using CssToInlineStyles
        $inliner     = new CssToInlineStyles();
        $inlinedHtml = $inliner->convert($html); //, $css);

        // Return the inlined HTML as the content
        return $this->subject('Votre devis ADN Voyage n°' . $this->quote->doc_id)
            ->html($inlinedHtml);
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
