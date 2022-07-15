<?php

namespace App\Repositories\V1\Filters\Traits;

trait ValidatesNullValue
{
    /**
     * Checks if the given value is a scalar type.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return array
     */
    protected static function validateNullValue(string $attribute, mixed $value): array
    {
        if (!is_null($value)) {
            return [trans('validation.absent', compact('attribute'))];
        }

        return [];
    }
}
