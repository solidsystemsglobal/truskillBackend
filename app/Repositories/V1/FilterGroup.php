<?php

namespace App\Repositories\V1;

use Iterator;
use Countable;
use ArrayAccess;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class FilterGroup implements Iterator, Countable, ArrayAccess
{
    /**
     * Indicates how the filter group should be applied on the query.
     * If true, it'll be applied using the orWhere clause.
     *
     * @var bool
     */
    public bool $orCond = false;

    /**
     * If specified, the items in the group will be applied to the given relation.
     *
     * @var string|null
     */
    public ?string $relation = null;

    /**
     * @var array<\App\Repositories\V1\Filter|\App\Repositories\V1\FilterGroup>
     */
    protected array $items;

    /**
     * The iterator's cursor.
     *
     * @var int
     */
    protected int $cursor = 0;

    /**
     * Makes a new instance of this class.
     *
     * @param  string|null $relation
     * @param  bool $orCond
     * @param  \App\Repositories\V1\Filter|\App\Repositories\V1\FilterGroup ...$items
     * @return static
     */
    public static function make(?string $relation, bool $orCond, Filter|FilterGroup ...$items): static
    {
        return new static($relation, $orCond, ...$items);
    }

    /**
     * Constructor.
     *
     * @param  string|null $relation
     * @param  bool $orCond
     * @param  \App\Repositories\V1\Filter|\App\Repositories\V1\FilterGroup ...$items
     * @return void
     */
    public function __construct(?string $relation, bool $orCond, Filter|FilterGroup ...$items)
    {
        $this->relation = $relation;
        $this->orCond = $orCond;
        $this->items = $items;
    }

    /**
     * Set the group filters.
     *
     * @param  \App\Repositories\V1\Filter|\App\Repositories\V1\FilterGroup ...$items
     * @return static
     */
    public function set(Filter|FilterGroup ...$items): static
    {
        $this->items = $items;
        $this->rewind();

        return $this;
    }

    /**
     * Returns all the group items.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Apply the filters on the given query.
     *
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return static
     */
    public function applyOn(QueryBuilder|EloquentBuilder $query): static
    {
        $args = [];
        $method = $this->orCond ? 'orWhere' : 'where';

        if ($this->relation) {
            $args[] = $this->relation;
            $method .= 'Has';
        }

        $args[] = function ($query) {
            foreach ($this->items as $filter) {
                $filter->applyOn($query);
            }
        };

        $query->{$method}(...$args);

        return $this;
    }

    /** {@inheritdoc} */
    public function rewind(): void
    {
        $this->cursor = 0;
    }

    /** {@inheritdoc} */
    public function current(): Filter|FilterGroup|null
    {
        return $this->items[$this->cursor] ?? null;
    }

    /** {@inheritdoc} */
    public function key(): int
    {
        return $this->cursor;
    }

    /** {@inheritdoc} */
    public function next(): void
    {
        ++$this->cursor;
    }

    /** {@inheritdoc} */
    public function valid(): bool
    {
        return isset($this->items[$this->cursor]);
    }

    /** {@inheritdoc} */
    public function count(): int
    {
        return count($this->items);
    }

    /** {@inheritdoc} */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } elseif (!is_int($offset)) {
            throw new \Exception('Illegal offset type');
        } elseif (!is_object($value) || !is_a($value, Filter::class) && !is_a($value, static::class)) {
            throw new \Exception('Illegal item value');
        } else {
            $this->items[$offset] = $value;
        }
    }

    /** {@inheritdoc} */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    /** {@inheritdoc} */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    /** {@inheritdoc} */
    public function offsetGet($offset)
    {
        return $this->items[$offset] ?? null;
    }
}
