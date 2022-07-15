<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RepositoryPagination implements Rule
{
    /**
     * @var array<string>
     */
    protected array $errors = [];

    /**
     * Parse the given string using the pagination regex.
     *
     * @param  string $value
     * @return array|null
     */
    public static function parse(string $value): ?array
    {
        if (preg_match('/^([1-9]\d*)\,([1-9]\d*)$/', $value, $matches)) {
            $page = intval($matches[1]);
            $perPage = intval($matches[2]);

            return [$page, $perPage];
        }

        return null;
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
            $this->errors[] = __('validation.string', compact('attribute'));
        } elseif ($params = self::parse($value)) {
            $this->validatePage($params[0], $attribute);
            $this->validatePerPage($params[1], $attribute);
        } else {
            $this->errors[] = __('validation.repository_pagination', compact('attribute'));
        }

        return empty($this->errors);
    }

    /**
     * Get the validation error message.
     *
     * @return string
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
     * Validate the page parameter.
     *
     * @param  int $page
     * @param  string $attribute
     * @return void
     */
    protected function validatePage(int $page, string $attribute): void
    {
        if ($page < 1) {
            $this->errors[] = __('validation.min.numeric', [
                'attribute' => "{$attribute}.page",
                'min' => 1,
            ]);
        }
    }

    /**
     * Validate the per page parameter.
     *
     * @param  int $perPage
     * @param  string $attribute
     * @return void
     */
    protected function validatePerPage(int $perPage, string $attribute): void
    {
        if ($perPage < 1 || $perPage > 1000) {
            $this->errors[] = __('validation.between.numeric', [
                'attribute' => "{$attribute}.perPage",
                'min' => 1,
                'max' => 1000,
            ]);
        }
    }
}
