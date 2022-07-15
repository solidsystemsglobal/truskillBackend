<?php

namespace App\Repositories\V1\Filters\Traits;

trait SanitizesScalarValue
{
    /**
     * Sanitize a value that should be an array.
     *
     * @param  mixed $value
     * @return mixed
     */
    protected function sanitizeScalarValue(mixed $value): mixed
    {
        if (is_string($value)) {
            $value = parseDateString($value);
        } elseif (!is_scalar($value)) {
            $value = '';
        }

        return $value;
    }
}
