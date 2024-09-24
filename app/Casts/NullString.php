<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * Custom cast for nullable string fields.
 * Empty strings are converted to NULL.
 */
class NullString implements CastsAttributes
{
    /**
     * Transform the attribute to its underlying model values.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return string|null
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        // Return the value as a float, or null if it's null
        return is_null($value) ? null : (string)$value;
    }

    /**
     * Transform the attribute from the provided value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return string|null
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        return $value === '' || is_null($value) ? null : (string)$value;
    }
}