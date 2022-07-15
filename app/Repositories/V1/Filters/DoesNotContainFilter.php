<?php

namespace App\Repositories\V1\Filters;

use App\Repositories\V1\Filter;
use App\Repositories\V1\Filters\Traits\SanitizesArrayOfScalarValues;
use App\Repositories\V1\Filters\Traits\ValidatesArrayOfScalarValues;

class DoesNotContainFilter extends Filter
{
    use SanitizesArrayOfScalarValues;
    use ValidatesArrayOfScalarValues;

    /** @inheritdoc */
    protected string $method = 'whereJsonDoesntContain';

    /** @inheritdoc */
    protected function sanitizeValue(mixed $value): mixed
    {
        if (is_array($value)) {
            return $this->sanitizeArrayOfScalarValues($value);
        } else {
            return $this->sanitizeScalarValue($value);
        }
    }

    /** @inheritdoc */
    public static function validateValue(string $attribute, mixed $value): array
    {
        if (is_array($value)) {
            return self::validateArrayOfScalarValues($attribute, $value);
        } elseif (is_scalar($value)) {
            return self::validateScalarValue($attribute, $value);
        } else {
            return [trans('validation.array_or_scalar', compact('attribute'))];
        }
    }
}
