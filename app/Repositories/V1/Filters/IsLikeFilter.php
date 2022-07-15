<?php

namespace App\Repositories\V1\Filters;

use App\Repositories\V1\Filter;
use App\Repositories\V1\Filters\Traits\SanitizesScalarValue;
use App\Repositories\V1\Filters\Traits\ValidatesScalarValue;

class IsLikeFilter extends Filter
{
    use SanitizesScalarValue;
    use ValidatesScalarValue;

    /** @inheritdoc */
    protected function sanitizeValue(mixed $value): mixed
    {
        return $this->sanitizeScalarValue($value);
    }

    /** @inheritdoc */
    protected function queryArgs(): array
    {
        return [$this->field, 'like', '%' . $this->value . '%'];
    }

    /** @inheritdoc */
    public static function validateValue(string $attribute, mixed $value): array
    {
        return self::validateNotEmptyScalarValue($attribute, $value);
    }
}
