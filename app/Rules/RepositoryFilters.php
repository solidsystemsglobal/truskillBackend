<?php

namespace App\Rules;

use App\Repositories\V1\Enums\FilterMode;
use Illuminate\Contracts\Validation\Rule;
use App\Repositories\V1\Facades\FilterFactory;

class RepositoryFilters implements Rule
{
    /**
     * @var array<string>
     */
    protected array $errors = [];

    /**
     * Parse the given filters string.
     *
     * @param  string $value
     * @return array|null
     */
    public static function parse(string $value): ?array
    {
        return json_decode($value, true);
    }

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->modes = FilterMode::cases();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!is_string($value)) {
            $this->errors[] = trans('validation.string', compact('attribute'));
        } elseif (empty($value)) {
            $this->errors[] = trans('validation.required', compact('attribute'));
        } elseif ($value = self::parse($value)) {
            $this->validateFiltersArray($value, $attribute);
        } else {
            $this->errors[] = trans('validation.json', compact('attribute'));
        }

        return empty($this->errors);
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        if (count($this->errors) === 1) {
            return $this->errors[0];
        } else {
            return $this->errors;
        }
    }

    /**
     * Determines if a valid filters array is given.
     *
     * @param  mixed $items
     * @param  string $attribute
     * @return void
     */
    protected function validateFiltersArray(mixed $items, string $attribute): void
    {
        if (!is_array($items) || !array_is_list($items)) {
            $this->errors[] = trans('validation.repository_filters', compact('attribute'));
        } else {
            foreach ($items as $i => $item) {
                $this->validateFilter($item, "{$attribute}.{$i}");
            }
        }
    }

    /**
     * Determines if a valid filter/filter-group is given.
     *
     * @param  mixed $item
     * @param  string $attribute
     * @return void
     */
    protected function validateFilter(mixed $item, string $attribute): void
    {
        if (!is_array($item)) {
            $this->errors[] = trans('validation.array', compact('attribute'));
        } else {
            $this->validateOrCond($item, $attribute);

            if (isset($item['items'])) {
                $this->validateFiltersArray($item['items'], "{$attribute}.items");

                if (isset($item['relation']) && !is_string($item['relation'])) {
                    $this->validateStringField($item['relation'], "{$attribute}.relation");
                }
            } else {
                $this->validateField($item, $attribute);

                if ($this->validateMode($item, $attribute)) {
                    $this->validateValue($item, $attribute);
                }
            }
        }
    }

    /**
     * Validates the given filter/filter-group condition.
     *
     * @param  array $filter
     * @param  string $attribute
     * @return void
     */
    protected function validateOrCond(array $filter, string $attribute): void
    {
        if (array_key_exists('orCond', $filter) && !is_bool($filter['orCond'])) {
            $attribute .= '.orCond';
            $this->errors[] = trans('validation.boolean', compact('attribute'));
        }
    }

    /**
     * Validates the given value to be a valid field string.
     *
     * @param  mixed $value
     * @param  string $attribute
     * @return void
     */
    protected function validateStringField(mixed $value, string $attribute): void
    {
        if (!is_string($value)) {
            $this->errors[] = trans('validation.string', compact('attribute'));
        } else {
            $len = strlen($value);

            if ($len < 1 || $len > 255) {
                $this->errors[] = trans('validation.between.string', [
                    'attribute' => $attribute,
                    'min' => 1,
                    'max' => 255,
                ]);
            } elseif (!preg_match('/^[A-Za-z_]$/', $value[0])) {
                $this->errors[] = trans('validation.starts_with', [
                    'attribute' => $attribute,
                    'values' => 'a-z, A-Z, _',
                ]);
            }
        }
    }

    /**
     * Validates the given filter field.
     *
     * @param  array $filter
     * @param  string $attribute
     * @return void
     */
    protected function validateField(array $filter, string $attribute): void
    {
        $attribute .= '.field';
        $mode = $filter['mode'] ?? null;
        $field = $filter['field'] ?? null;

        if (!in_array($mode, $this->modes)) {
            if (!is_null($field)) {
                $this->validateStringField($field, $attribute);
            }
        } elseif (!isset($field)) {
            $this->errors[] = trans('validation.required', compact('attribute'));
        } else {
            $this->validateStringField($field, $attribute);
        }
    }

    /**
     * Validates the given filter mode.
     *
     * @param  array $filter
     * @param  string $attribute
     * @return bool  TRUE if mode is valid, otherwise FALSE.
     */
    protected function validateMode(array $filter, string $attribute): bool
    {
        $attribute .= '.mode';

        if (!isset($filter['mode'])) {
            $this->errors[] = trans('validation.required', compact('attribute'));
        } elseif (!FilterFactory::isRegisteredMode($filter['mode'])) {
            $this->errors[] = trans('validation.in', compact('attribute'));
        } else {
            return true;
        }

        return false;
    }

    /**
     * Validates the given filter value.
     *
     * @param  array $filter
     * @param  string $attribute
     * @return void
     */
    protected function validateValue(array $filter, string $attribute): void
    {
        $attribute .= '.value';
        $value = $filter['value'] ?? null;
        $filterClassName = FilterFactory::getClass($filter['mode']);

        if ($errors = $filterClassName::validateValue($attribute, $value)) {
            $this->errors = [...$this->errors, ...$errors];
        }
    }
}
