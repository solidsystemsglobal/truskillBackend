<?php

namespace App\Repositories\V1\Filters;

use App\Repositories\V1\Filter;
use App\Repositories\V1\Filters\Traits\ValidatesNullValue;

class IsNullFilter extends Filter
{
    use ValidatesNullValue;

    /** @inheritdoc */
    protected string $method = 'whereNull';

    /** @inheritdoc */
    protected function sanitizeValue(mixed $value): mixed
    {
        return null;
    }

    /** @inheritdoc */
    protected function queryArgs(): array
    {
        return [$this->field];
    }

    /** @inheritdoc */
    public static function validateValue(string $attribute, mixed $value): array
    {
        return self::validateNullValue($attribute, $value);
    }
}
