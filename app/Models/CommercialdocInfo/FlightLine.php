<?php

namespace App\Models\CommercialdocInfo;

use App\Models\CommercialdocInfo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class FlightLine extends CommercialdocInfo
{
    protected function date(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->data['date'] ?? null,
            set: fn ($value) => $this->data['date'] = $value
        )->withoutObjectCaching();
    }

    protected function flightNum(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->data['flight_num'] ?? null,
            set: fn ($value) => $this->data['flight_num'] = $value
        )->withoutObjectCaching();
    }

    protected function origin(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->data['origin'] ?? null,
            set: fn ($value) => $this->data['origin'] = $value
        )->withoutObjectCaching();
    }

    protected function destination(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->data['dest'] ?? null,
            set: fn ($value) => $this->data['dest'] = $value
        )->withoutObjectCaching();
    }

    protected function departureTime(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->data['dep_time'] ?? null,
            set: fn ($value) => $this->data['dep_time'] = $value
        )->withoutObjectCaching();
    }

    protected function arrivalTime(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->data['arr_time'] ?? null,
            set: fn ($value) => $this->data['arr_time'] = $value
        )->withoutObjectCaching();
    }
}
