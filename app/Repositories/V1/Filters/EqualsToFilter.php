<?php

namespace App\Repositories\V1\Filters;

use App\Repositories\V1\Filter;
use App\Repositories\V1\Filters\Traits\SanitizesScalarValue;
use App\Repositories\V1\Filters\Traits\ValidatesScalarValue;

class EqualsToFilter extends Filter
{
    use SanitizesScalarValue;
    use ValidatesScalarValue;

    /** @inheritdoc */
    protected function sanitizeValue(mixed $value): mixed
    {
        return $this->sanitizeScalarValue($value);
    }

    /** @inheritdoc */
    public static function validateValue(string $attribute, mixed $value): array
    {
        return self::validateScalarValue($attribute, $value);
    }
}
