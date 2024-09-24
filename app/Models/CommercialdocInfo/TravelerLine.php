<?php

namespace App\Models\CommercialdocInfo;

use App\Enums\CommercialdocInfoType;
use App\Models\CommercialdocInfo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Http\Request;

class TravelerLine extends CommercialdocInfo
{
    static $type = CommercialdocInfoType::TravelerLine->value;

    /**
     * Label of the reservation ID. E.g. Hotel, Transfer
     * 15 characters should be enough.
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->data['name'] ?? null,
            set: fn ($value) => $this->data['name'] = $value
        )->withoutObjectCaching();
    }

    /**
     * Reservation ID. A string of to 24 characters
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function ticketNum(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->data['ticket_num'] ?? null,
            set: fn ($value) => $this->data['ticket_num'] = $value
        )->withoutObjectCaching();
    }
}
