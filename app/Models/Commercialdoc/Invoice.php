<?php

namespace App\Models\Commercialdoc;

use App\Enums\CommercialdocStatus;
use App\Models\Commercialdoc;
use App\Models\Reservation;

class Invoice extends Commercialdoc
{

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Append the additional attribute
        $this->appends[] = 'header_specific_lines';
        $this->appends[] = 'travelers';
    }

    public function getHeaderSpecificLinesAttribute()
    {
        return array_filter([
            ['label' => 'Facture n°', 'value' => $this->doc_id],
            ['label' => 'Date', 'value' => $this->created_lclzed],
            ['label' => 'Echéance', 'value' => $this->deadline_lcl],
            ['label' => 'Monnaie', 'value' => "{$this->currency->code} ({$this->currency->nom_monnaie})"],
        ], fn($line) => $line['value']);
    }

    public function getTravelersAttribute()
    {
        return $this->reservation->participants->sortBy('idx')->sortByDesc('adulte')->values();
    }

    public function getPDF() {
        // TODO: return PDF
    }
}
