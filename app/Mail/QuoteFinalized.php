<?php

namespace App\Mail;

use App\Models\Commercialdoc;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuoteFinalized extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct(
        public Commercialdoc $doc,
    ) {}

    public function build()
    {
        return $this->subject('Votre devis ADN Voyge est finalisé')
            ->view('emails.quote_finalized')
            ->with(['doc' => $this->doc]);
    }
}
