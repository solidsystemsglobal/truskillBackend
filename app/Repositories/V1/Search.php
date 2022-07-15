<?php

namespace App\Repositories\V1;

use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

/**
 * Class used to describe repository search parameters.
 */
class Search
{
    /**
     * @var array<string>
     */
    public array $fields = [];

    /**
     * @var array<bool>
     */
    protected $fieldHasRelation = [];

    /**
     * Makes a new instance of this class.
     *
     * @param  string $text
     * @param  string ...$fields
     * @return static
     */
    public static function make(string $text, string ...$fields): static
    {
        return new static($text, ...$fields);
    }

    /**
     * Constructor.
     *
     * @param  string $text
     * @param  string ...$fields
     * @return void
     */
    public function __construct(public string $text, string ...$fields)
    {
        $this->fields = $fields;
    }

    /**
     * Apply the search on the given query.
     *
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return static
     */
    public function applyOn(QueryBuilder|EloquentBuilder $query): static
    {
        $fieldsCount = count($this->fields);

        if ($fieldsCount === 1) {
            $this->searchField($query, $this->fields[0], false);
        } elseif ($fieldsCount > 1) {
            $query->where(function ($query) {
                foreach ($this->fields as $i => $field) {
                    $this->searchField($query, $field, boolval($i));
                }
            });
        }

        return $this;
    }

    /**
     * Search in the given field.
     *
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @param  string $field
     * @param  bool $orCond
     * @return void
     */
    protected function searchField(QueryBuilder|EloquentBuilder $query, string $field, bool $orCond): void
    {
        $method = $orCond ? 'orWhere' : 'where';

        if (str_contains($field, '.')) {
            if (is_a($query, EloquentBuilder::class)) {
                $lastDotPos = strrpos($field, '.');
                $relation = substr($field, 0, $lastDotPos);
                $field = substr($field, $lastDotPos + 1);

                $method .= 'Has';
                $query->{$method}($relation, function ($query) use ($field) {
                    $query->where($field, 'like', '%' . $this->text . '%');
                });
            } else {
                /**
                 * @todo Implement relations search logic for the plain query builder if needed.
                 */
            }
        } else {
            $query->{$method}($field, 'like', '%' . $this->text . '%');
        }
    }
}
