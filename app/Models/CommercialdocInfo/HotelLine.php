<?php

namespace App\Models\CommercialdocInfo;

use App\Models\CommercialdocInfo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class HotelLine extends CommercialdocInfo
{
    protected function checkinDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->data['checkin_date'] ?? null,
            set: fn ($value) => $this->data['checkin_date'] = $value
        )->withoutObjectCaching();
    }

    protected function checkoutDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->data['checkout_date'] ?? null,
            set: fn ($value) => $this->data['checkout_date'] = $value
        )->withoutObjectCaching();
    }

    protected function hotel(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->data['hotel'] ?? null,
            set: fn ($value) => $this->data['hotel'] = $value
        )->withoutObjectCaching();
    }

    protected function roomType(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->data['room_type'] ?? null,
            set: fn ($value) => $this->data['room_type'] = $value
        )->withoutObjectCaching();
    }

    protected function mealType(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->data['meal_type'] ?? null,
            set: fn ($value) => $this->data['meal_type'] = $value
        )->withoutObjectCaching();
    }
}
