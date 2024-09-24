<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class AdminNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        string $subject,
        private string $body,
    ) {
        $this->subject = $subject;
    }

    function resolveButtonPlaceholders($text)
    {
        $text = preg_replace_callback('/\[button\|(.*?)\|(.*?)\]/', function ($matches) {
            [, $route, $text] = $matches;
            return "<a class=\"btn\" href=\"$route\" class=\"btn\">$text</a>";
        }, $text);

        return $text;
    }

    /**
     * Get the message content definition.
     */
    public function build()
    {
        $body = collect(preg_split("/\n\s*\n\s*/", $this->body))
            ->map(fn($line) => htmlentities(trim($line)))
            ->map(fn($line) => '<p>' .
                preg_replace('/\s*\n\s*/', "<br/>\n", $line) .
                "<p>\n\n")
            ->map([$this, 'resolveButtonPlaceholders'])
            ->join("\n");

        // Render the Blade template into HTML
        $html = view('emails.adminNotification', ['content' => $body])->render();

        // Inline the CSS using CssToInlineStyles
        $inliner     = new CssToInlineStyles();
        $inlinedHtml = $inliner->convert($html); //, $css);

        $this->subject($this->subject);

        // Return the inlined HTML as the content
        return $this->html($inlinedHtml);
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
