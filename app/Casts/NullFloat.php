<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * Custom cast for nullable float fields.
 * Empty strings are converted to NULL.
 */
class NullFloat implements CastsAttributes
{
    /**
     * Transform the attribute to its underlying model values.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return float|null
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?float
    {
        // Return the value as a float, or null if it's null
        return is_null($value) ? null : (float)$value;
    }

    /**
     * Transform the attribute from the provided value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return float|null
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?float
    {
        return $value === '' || is_null($value) ? null : (float)$value;
    }
}
