<?php

namespace App\Repositories\V1\Filters;

use App\Repositories\V1\Filter;
use App\Repositories\V1\Filters\Traits\SanitizesScalarValue;
use App\Repositories\V1\Filters\Traits\ValidatesArrayOfScalarValues;

class InRangeFilter extends Filter
{
    use SanitizesScalarValue;
    use ValidatesArrayOfScalarValues;

    /** @inheritdoc */
    protected string $method = 'whereBetween';

    /** @inheritdoc */
    protected function sanitizeValue(mixed $value): mixed
    {
        if (is_array($value)) {
            $value = array_values($value);

            return [
                $this->sanitizeScalarValue($value[0]),
                $this->sanitizeScalarValue($value[1]),
            ];
        } else {
            return [0, 0];
        }
    }

    /** @inheritdoc */
    public static function validateValue(string $attribute, mixed $value): array
    {
        return self::validateArrayOfScalarValues($attribute, $value, 2);
    }
}
