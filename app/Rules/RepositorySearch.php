<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RepositorySearch implements Rule
{
    /**
     * @var array<string>
     */
    protected array $errors = [];

    /**
     * Parse the given string using the search regex.
     *
     * @param  string $value
     * @return array|null
     */
    public static function parse(string $value): ?array
    {
        $regex = '/(?(DEFINE)(?<field>(?:[a-zA-Z_]\w*\.)*[a-zA-Z_]\w*))^(.+)\,\[((?:(?P>field)\,)*(?P>field))\]$/';

        if (preg_match($regex, $value, $matches)) {
            $text = $matches[2];
            $fields = explode(',', $matches[3]);

            return [$text, $fields];
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
            $this->errors[] = trans('validation.string', ['attribute' => $attribute]);
        } elseif ($params = self::parse($value)) {
            /** @var array $params */
            $this->validateText($params[0], $attribute);
            $this->validateFields($params[1], $attribute);
        } else {
            $this->errors[] = trans('validation.repository_search', compact('attribute'));
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
     * Validate the search text parameter.
     *
     * @param  string $text
     * @param  string $attribute
     * @return void
     */
    protected function validateText(string $text, string $attribute): void
    {
        if (strlen($text) > 255) {
            $this->errors[] = trans('validation.max.string', [
                'attribute' => "{$attribute}.text",
                'max' => 255,
            ]);
        }
    }

    /**
     * Validate the search fields parameter.
     *
     * @param  array $fields
     * @param  string $attribute
     * @return void
     */
    protected function validateFields(array $fields, string $attribute): void
    {
        foreach ($fields as $i => $field) {
            if (strlen($field) > 255) {
                $this->errors[] = trans('validation.max.string', [
                    'attribute' => "{$attribute}.fields.{$i}",
                    'max' => 255,
                ]);
            }
        }
    }
}
