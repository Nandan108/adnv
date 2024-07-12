<?php

namespace App\Models\CommercialdocInfo;

use App\Models\CommercialdocInfo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class TransfertLine extends CommercialdocInfo
{
    protected function departureDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->data['dep_date'] ?? null,
            set: fn ($value) => $this->data['dep_date'] = $value
        )->withoutObjectCaching();
    }

    protected function arrivalDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->data['arr_date'] ?? null,
            set: fn ($value) => $this->data['arr_date'] = $value
        )->withoutObjectCaching();
    }

    protected function duration(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->data['duration'] ?? null,
            set: fn ($value) => $this->data['duration'] = $value
        )->withoutObjectCaching();
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->data['description'] ?? null,
            set: fn ($value) => $this->data['description'] = $value
        )->withoutObjectCaching();
    }

    protected function vehicle(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->data['vehicle'] ?? null,
            set: fn ($value) => $this->data['vehicle'] = $value
        )->withoutObjectCaching();
    }
}
