<?php

namespace App\Models\CommercialdocInfo;

use App\Models\CommercialdocInfo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Trip extends CommercialdocInfo
{
    protected function info(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->data['info'] ?? null,
            set: fn ($value) => $this->data['info'] = $value
        )->withoutObjectCaching();
    }
}
