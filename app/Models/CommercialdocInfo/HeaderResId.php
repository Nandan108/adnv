<?php

namespace App\Models\CommercialdocInfo;

use App\Models\CommercialdocInfo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class HeaderResId extends CommercialdocInfo
{
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->data['name'] ?? null,
            set: fn ($value) => $this->data['name'] = $value
        )->withoutObjectCaching();
    }

    protected function id(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->data['id'] ?? null,
            set: fn ($value) => $this->data['id'] = $value
        )->withoutObjectCaching();
    }
}
