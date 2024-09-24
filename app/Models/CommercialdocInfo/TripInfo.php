<?php

namespace App\Models\CommercialdocInfo;

use App\Enums\CommercialdocInfoType;
use App\Models\CommercialdocInfo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class TripInfo extends CommercialdocInfo
{
    static $type = CommercialdocInfoType::TripInfo->value;

    protected function comments(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->data['info'] ?? null,
            set: fn ($value) => $this->data['info'] = $value
        )->withoutObjectCaching();
    }
}
