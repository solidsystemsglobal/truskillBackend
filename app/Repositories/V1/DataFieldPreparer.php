<?php

namespace App\Repositories\V1;

use Illuminate\Support\Str;

class DataFieldPreparer
{
    /**
     * Makes an instance of this class.
     *
     * @param  \App\Repositories\V1\DataFieldMap|null $fieldMap
     * @param  bool $snakeCase
     * @return static
     */
    public static function make(
        ?DataFieldMap $fieldMap = null,
        bool $snakeCase = true
    ): static {
        return new static($fieldMap, $snakeCase);
    }

    /**
     * Makes an instance of this class.
     *
     * @param  \App\Repositories\V1\DataFieldMap|null $fieldMap
     * @param  bool $snakeCase = true
     * @return static
     */
    public function __construct(
        public ?DataFieldMap $fieldMap = null,
        public bool $snakeCase = true
    ) {
        //
    }

    /**
     * Prepares the given field.
     *
     * @param  string $field
     * @return string
     */
    public function prepare(string $field): string
    {
        if ($this->fieldMap) {
            $field = $this->fieldMap->match($field);
        }

        if (str_contains($field, '.')) {
            $lastDotPos = strrpos($field, '.');
            $prefix = substr($field, 0, $lastDotPos + 1);
            $field = substr($field, $lastDotPos + 1);
        } else {
            $prefix = '';
            $field = $field;
        }

        if ($this->snakeCase) {
            $field = Str::snake($field);
        }

        return $prefix . $field;
    }
}
