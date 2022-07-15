<?php

namespace App\Rules;

use App\Repositories\V1\Sort;
use Illuminate\Contracts\Validation\Rule;

class RepositorySort implements Rule
{
    /**
     * @var array<string>
     */
    protected array $errors = [];

    /**
     * Parse the given string using the sort regex.
     *
     * @param  string $value
     * @return array|null
     */
    public static function parse(string $value): ?array
    {
        $dirs = join('|', Sort::getDirs());
        $regex = "/^((?:[a-zA-Z_]\w*\.)*[a-zA-Z_]\w*)\,({$dirs})$/";

        if (preg_match($regex, $value, $matches)) {
            $field = $matches[1];
            $dir = $matches[2];

            return [$field, $dir];
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
        $dirs = join('|', Sort::getDirs());
        $regex = "/^((?:[a-zA-Z_]\w*\.)*[a-zA-Z]\w*)\,({$dirs})$/";

        if (!is_string($value)) {
            $this->errors[] = trans('validation.string', compact('attribute'));
        } elseif ($params = self::parse($value)) {
            if (strlen($params[0]) > 255) {
                $this->errors[] = trans('validation.max.string', [
                    'attribute' => "{$attribute}.field",
                    'max' => 255,
                ]);
            }
        } else {
            $this->errors[] = trans('validation.repository_sort', compact('attribute'));
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
}
