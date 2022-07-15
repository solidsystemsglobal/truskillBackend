<?php

namespace App\Repositories\V1\Filters;

use App\Repositories\V1\Filter;
use App\Repositories\V1\Filters\Traits\SanitizesArrayOfScalarValues;
use App\Repositories\V1\Filters\Traits\ValidatesArrayOfScalarValues;

class IncludedInFilter extends Filter
{
    use SanitizesArrayOfScalarValues;
    use ValidatesArrayOfScalarValues;

    /** @inheritdoc */
    protected string $method = 'whereIn';

    /** @inheritdoc */
    protected function sanitizeValue(mixed $value): mixed
    {
        return $this->sanitizeArrayOfScalarValues($value);
    }

    /** @inheritdoc */
    public static function validateValue(string $attribute, mixed $value): array
    {
        return self::validateArrayOfScalarValues($attribute, $value);
    }
}
