<?php

namespace App\Repositories\V1;

use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

abstract class Filter
{
    /**
     * Indicates how the filter should be applied on the query.
     * If true, it'll be applied by the or where clause.
     *
     * @var bool
     */
    public bool $orCond = false;

    /**
     * The filter field relation.
     *
     * @var string|null
     */
    public ?string $relation = null;

    /**
     * The filter field.
     *
     * @var string|null
     */
    public ?string $field = null;

    /**
     * The filter value.
     *
     * @var mixed
     */
    public mixed $value = null;

    /**
     * The filter method.
     *
     * @var string
     */
    protected string $method = 'where';

    /**
     * Make an instance of this class.
     *
     * @param  string|null $field
     * @param  mixed $value
     * @param  bool $orCond
     * @return static
     */
    public static function make(?string $field, mixed $value, bool $orCond): static
    {
        return new static($field, $value, $orCond);
    }

    /**
     * Validates the filter value.
     *
     * @param  string $attribute  The name of the attribute under validation.
     * @param  mixed $value  The value to validate.
     * @return array<string>  The error messages array.
     */
    abstract public static function validateValue(string $attribute, mixed $value): array;

    /**
     * Constructor.
     *
     * @param  string|null $field
     * @param  mixed $value
     * @param  bool $orCond
     * @return void
     */
    public function __construct(?string $field, mixed $value, bool $orCond)
    {
        $this->orCond = $orCond;
        $this->setField($field);
        $this->value = $this->sanitizeValue($value);
    }

    /**
     * Apply the filter on the given query.
     *
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return static
     */
    public function applyOn(QueryBuilder|EloquentBuilder $query): static
    {
        $method = $this->orCond ? 'or' . ucfirst($this->method) : $this->method;
        $args = $this->queryArgs();

        if ($this->relation) {
            if (is_a($query, EloquentBuilder::class)) {
                $query->whereHas($this->relation, fn($q) => $q->{$method}(...$args));
            } else {
                /**
                 * @todo Implement relations filtering logic for the plain query builder if needed.
                 */
            }
        } else {
            $query->{$method}(...$args);
        }

        return $this;
    }

    /**
     * Sanitizes the filter value.
     *
     * @param  mixed $value
     * @return mixed
     */
    abstract protected function sanitizeValue(mixed $value): mixed;

    /**
     * Returns the filter query arguments.
     *
     * @return array
     */
    protected function queryArgs(): array
    {
        return [$this->field, $this->value];
    }

    /**
     * Sets the filter field.
     *
     * @param  string $field
     * @return void
     */
    protected function setField(string $field): void
    {
        if (str_contains($field, '.')) {
            $lastDotPos = strrpos($field, '.');
            $this->relation = substr($field, 0, $lastDotPos);
            $this->field = substr($field, $lastDotPos + 1);
        } else {
            $this->field = $field;
        }
    }
}
