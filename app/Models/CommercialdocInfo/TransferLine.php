<?php

namespace App\Models\CommercialdocInfo;

use App\Enums\CommercialdocInfoType;
use App\Models\CommercialdocInfo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class TransferLine extends CommercialdocInfo
{
    static $type = CommercialdocInfoType::TransferLine->value;

    protected function pickup(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->data['pickup'] ?? null,
            set: fn($value) => $this->data['pickup'] = $value
        );
    }

    protected function dropoff(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->data['dropoff'] ?? null,
            set: fn($value) => $this->data['dropoff'] = $value
        );
    }

    /**
     * Just a string (e.g. "1 hour")
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function duration(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->data['duration'] ?? null,
            set: fn($value) => $this->data['duration'] = $value
        );
    }

    protected function route(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->data['route'] ?? null,
            set: fn($value) => $this->data['route'] = $value
        );
    }

    /**
     * Name of vehicle used. ("Bus", "Car", "Speedboat", ...)
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function vehicle(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->data['vehicle'] ?? null,
            set: fn($value) => $this->data['vehicle'] = $value
        );
    }
}
