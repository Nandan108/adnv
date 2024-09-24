<?php

namespace App\Models\CommercialdocInfo;

use App\Enums\CommercialdocInfoType;
use App\Models\CommercialdocInfo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class FlightLine extends CommercialdocInfo
{
    static $type = CommercialdocInfoType::FlightLine->value;

    protected function date(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->data['date'] ?? null,
            set: fn($value) => $this->data['date'] = $value
        ); //->withoutObjectCaching();
    }

    protected function airline(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->data['airline'] ?? null,
            set: fn($value) => $this->data['airline'] = $value
        ); //->withoutObjectCaching();
    }

    protected function flightNum(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->data['flight_num'] ?? null,
            set: fn($value) => $this->data['flight_num'] = $value
        ); //->withoutObjectCaching();
    }

    /**
     * The fields 'origin' and 'dest' are 3-letter airport codes.
     * Ideally, for validation, we'd want to check if the airport
     * actually exists in the database.
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function origin(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->data['origin'] ?? null,
            set: fn($value) => $this->data['origin'] = $value
        ); //->withoutObjectCaching();
    }

    /**
     * This is field is a 3-letter airport code
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function destination(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->data['dest'] ?? null,
            set: fn($value) => $this->data['dest'] = $value
        ); //->withoutObjectCaching();
    }

    protected function departureTime(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->data['dep_time'] ?? null,
            set: fn($value) => $this->data['dep_time'] = $value
        ); //->withoutObjectCaching();
    }

    protected function arrivalTime(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->data['arr_time'] ?? null,
            set: fn($value) => $this->data['arr_time'] = $value
        ); //->withoutObjectCaching();
    }
    protected function arrivalNextDay(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->data['arr_next_day'] ?? null,
            set: fn($value) => $this->data['arr_next_day'] = (int)(bool)$value
        ); //->withoutObjectCaching();
    }
}
