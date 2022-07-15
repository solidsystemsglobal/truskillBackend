<?php

namespace App\Repositories\V1;

use App\Repositories\V1\Facades\QueryHelper;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

/**
 * Class used to describe repository sorting parameters.
 */
class Sort
{
    /**
     * The sorting field relation.
     *
     * @var string|null
     */
    protected ?string $relation = null;

    /**
     * The sorting field.
     *
     * @var string|null
     */
    protected string $field;

    /**
     * Valid sorting directions.
     *
     * @var string
     */
    const DIR_ASC = 'asc';
    const DIR_DESC = 'desc';

    /**
     * Makes a new instance of this class.
     *
     * @param  string $field
     * @param  string $dir
     * @return static
     */
    public static function make(string $field, string $dir): static
    {
        return new static($field, $dir);
    }

    /**
     * Constructor.
     *
     * @param  string $field
     * @param  string $dir
     * @return void
     */
    public function __construct(string $field, public string $dir)
    {
        $this->setField($field);
    }

    /**
     * Get the valid sort directions.
     *
     * @return array<string>
     */
    public static function getDirs(): array
    {
        return [
            self::DIR_ASC,
            self::DIR_DESC,
        ];
    }

    /**
     * Apply the sorting on the given query.
     *
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return static
     */
    public function applyOn(QueryBuilder|EloquentBuilder $query): static
    {
        if (isset($this->relation)) {
            if (is_a($query, EloquentBuilder::class)) {
                $query->leftJoinRelation($this->relation)->distinct();
                $lastJoin = last($query->getQuery()->joins);
                $lastJoinTable = QueryHelper::tableName($lastJoin);
                $field = $lastJoinTable . '.' . $this->field;
                $query->orderBy($field, $this->dir);
            } else {
                /**
                 * @todo Implement relations sorting logic for the plain query builder if needed.
                 */
            }
        } else {
            $query->orderBy($this->field, $this->dir);
        }

        return $this;
    }

    /**
     * Sets the sorting field.
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
