<?php

namespace App\Models\CommercialdocInfo;

use App\Enums\CommercialdocInfoType;
use App\Models\CommercialdocInfo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class TransfertComments extends CommercialdocInfo
{
    static $type = CommercialdocInfoType::TransfertComments->value;

    protected function comments(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->data['comments'] ?? null,
            set: fn ($value) => $this->data['comments'] = $value
        )->withoutObjectCaching();
    }
}
