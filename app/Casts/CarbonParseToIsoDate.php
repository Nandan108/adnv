<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Database\Eloquent\CastsInboundAttributes;
use Carbon\Carbon;

class CarbonParseToIsoDate implements CastsAttributes, CastsInboundAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return string|null
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if ($value === null) return null;
        if ($value instanceof Carbon) return $value->format('Y-m-d');
        return Carbon::parse($value)->format('Y-m-d');
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return string|null
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if ($value === null) return null;
        if ($value instanceof Carbon) return $value;
        return Carbon::parse($value);
    }
}
