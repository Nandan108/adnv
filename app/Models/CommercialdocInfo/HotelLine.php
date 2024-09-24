<?php

namespace App\Models\CommercialdocInfo;

use App\Enums\CommercialdocInfoType;
use App\Models\CommercialdocInfo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class HotelLine extends CommercialdocInfo
{
    static $type = CommercialdocInfoType::HotelLine->value;

    protected function checkinDate(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->data['checkin'] ?? null,
            set: fn($value) => $this->data['checkin'] = $value
        );
    }

    protected function checkoutDate(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->data['checkout'] ?? null,
            set: fn($value) => $this->data['checkout'] = $value
        );
    }

    /**
     * Hotel name
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function hotel(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->data['hotel'] ?? null,
            set: fn($value) => $this->data['hotel'] = $value
        );
    }

    /**
     * Room type name
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function roomType(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->data['room_type'] ?? null,
            set: fn($value) => $this->data['room_type'] = $value
        );
    }

    /**
     * Meal Plan
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function mealType(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => $this->data['meal_type'] ?? null,
            set: fn($value) => $this->data['meal_type'] = $value
        );
    }
}
