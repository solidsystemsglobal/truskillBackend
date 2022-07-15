<?php

namespace App\Repositories\V1\Filters\Traits;

trait ValidatesScalarValue
{
    /**
     * Checks if the given value is a scalar type and isn't empty.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return array
     */
    protected static function validateNotEmptyScalarValue(string $attribute, mixed $value): array
    {
        if ($errors = self::validateScalarValue($attribute, $value)) {
            return $errors;
        } elseif ($value === '') {
            return [trans('validation.required', compact('attribute'))];
        }

        return [];
    }

    /**
     * Checks if the given value is a scalar type.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return array
     */
    protected static function validateScalarValue(string $attribute, mixed $value): array
    {
        if (!is_scalar($value)) {
            return [trans('validation.scalar', compact('attribute'))];
        }

        return [];
    }
}
