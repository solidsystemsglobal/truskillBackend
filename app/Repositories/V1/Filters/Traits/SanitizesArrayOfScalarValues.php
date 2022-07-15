<?php

namespace App\Repositories\V1\Filters\Traits;

trait SanitizesArrayOfScalarValues
{
    use SanitizesScalarValue;

    /**
     * Sanitize a value that should be an array.
     *
     * @param  mixed $value
     * @return mixed
     */
    protected function sanitizeArrayOfScalarValues(mixed $value): mixed
    {
        if (is_array($value)) {
            $value = array_values($value);

            foreach ($value as $i => $item) {
                $value[$i] = $this->sanitizeScalarValue($item);
            }

            return $value;
        } else {
            return [];
        }
    }
}
