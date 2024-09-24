<?php

namespace App\Models\CommercialdocInfo;

use App\Enums\CommercialdocInfoType;
use App\Models\CommercialdocInfo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Http\Request;

class HeaderResId extends CommercialdocInfo
{
    static $type = CommercialdocInfoType::HeaderResId->value;

    /**
     * Label of the reservation ID. E.g. Hotel, Transfer
     * 15 characters should be enough.
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->data['name'] ?? null,
            set: fn($value) => $this->data['name'] = $value
        )->withoutObjectCaching();
    }

    /**
     * Reservation ID. A string of to 24 characters
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function id(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->data['id'] ?? null,
            set: fn($value) => $this->data['id'] = $value
        )->withoutObjectCaching();
    }
}
