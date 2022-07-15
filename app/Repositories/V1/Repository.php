<?php

namespace App\Repositories\V1;

use stdClass;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\Paginator;
use App\Repositories\V1\Facades\QueryHelper;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class Repository
{
    /**
     * Searching parameters.
     *
     * @var \App\Repositories\V1\Search|null
     */
    protected ?Search $search = null;

    /**
     * Sorting parameters.
     *
     * @var \App\Repositories\V1\Sort|null
     */
    protected ?Sort $sort = null;

    /**
     * Filters.
     *
     * @var array<\App\Repositories\V1\FilterGroup|\App\Repositories\V1\Filter>
     */
    protected array $filters = [];

    /**
     * Makes a new instance of this class.
     *
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return static
     */
    public static function make(QueryBuilder|EloquentBuilder $query): static
    {
        return new static($query);
    }

    /**
     * Constructor.
     *
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    public function __construct(protected QueryBuilder|EloquentBuilder $query)
    {
        //
    }

    /**
     * Fires when an attempt is made to access private or non-existent methods.
     *
     * @param  string $name
     * @param  array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments): mixed
    {
        try {
            $result = $this->query->{$name}(...$arguments);

            if (
                is_a($result, QueryBuilder::class) ||
                is_a($result, EloquentBuilder::class)
            ) {
                $this->query($result);

                return $this;
            } else {
                return $result;
            }
        } catch (\Exception $exp) {
            throw new \Exception(self::class . '::' . $name . '() method not exists.');
        }
    }

    /**
     * Get or set the query instance.
     *
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder|null $query
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query(QueryBuilder|EloquentBuilder $query = null): QueryBuilder|EloquentBuilder
    {
        if ($query) {
            $this->query = $query;
        }

        return $this->query;
    }

    /**
     * Set the fields to select with the query.
     *
     * @param  string ...$fields
     * @return static
     */
    public function select(string ...$fields): static
    {
        $this->query->select($fields);

        return $this;
    }

    /**
     * Set the relationships that should be eager loaded after the query execution.
     *
     * @param  string|array $relations
     * @param  \Closure|null $callback
     * @return static
     */
    public function with(string|array $relations, \Closure $callback = null): static
    {
        if (is_a($this->query, EloquentBuilder::class)) {
            $this->query->with($relations, $callback);
        } else {
            /**
             * @todo
             */
        }

        return $this;
    }

    /**
     * Set to get distinct query results.
     *
     * @return static
     */
    public function distinct(): static
    {
        $this->query->distinct();

        return $this;
    }

    /**
     * Limit the query results.
     *
     * @param  int $count
     * @return static
     */
    public function limit(int $count): static
    {
        $this->query->limit($count);

        return $this;
    }

    /**
     * Modify query sorting.
     *
     * @param  \App\Repositories\V1\Sort|null $sort
     * @return static
     */
    public function sort(?Sort $sort): static
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Determines if sorting is set in the repository.
     *
     * @return bool
     */
    public function isSorted(): bool
    {
        return isset($this->sort);
    }

    /**
     * Modify query search parameters.
     *
     * @param  \App\Repositories\V1\Search|null $search
     * @return static
     */
    public function search(?Search $search): static
    {
        $this->search = $search;

        return $this;
    }

    /**
     * Determines if search parameters are set in the repository.
     *
     * @return bool
     */
    public function isSearched(): bool
    {
        return isset($this->search);
    }

    /**
     * Modify query filters.
     *
     * @param  \App\Repositories\V1\FilterGroup|\App\Repositories\V1\Filter ...$filters
     * @return static
     */
    public function filter(FilterGroup|Filter ...$filters): static
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * Determines if filters are set in the repository.
     *
     * @return bool
     */
    public function isFiltered(): bool
    {
        return !empty($this->filters);
    }

    /**
     * Get the query results.
     *
     * @return \Illuminate\Support\Collection
     */
    public function get(): Collection
    {
        $this->prepareForFetch();

        return $this->query->get();
    }

    /**
     * Get paginated query results.
     *
     * @param  \App\Repositories\V1\Pagination $pagination
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function paginate(Pagination $pagination): Paginator
    {
        $this->prepareForFetch();

        return $this->query->paginate(
            perPage: $pagination->perPage,
            page: $pagination->page
        );
    }

    /**
     * Get the query results via lazy collection.
     *
     * @return \Illuminate\Support\LazyCollection
     */
    public function cursor(): LazyCollection
    {
        $this->prepareForFetch();

        return $this->query->cursor();
    }

    /**
     * Get the query results in chunks via lazy collection.
     *
     * @param  int $chunkSize
     * @return \Illuminate\Support\LazyCollection
     */
    public function lazy(int $chunkSize = 1000): LazyCollection
    {
        $this->prepareForFetch();

        return $this->query->lazy($chunkSize);
    }

    /**
     * Get the query results in chunks.
     *
     * @param  int $count
     * @param  callable $callback
     * @return bool
     */
    public function chunk(int $count, callable $callback): bool
    {
        $this->prepareForFetch();

        return $this->query->chunk($count, $callback);
    }

    /**
     * Get the query results count.
     *
     * @return int
     */
    public function count(): int
    {
        $this->prepareForFetch();

        return $this->query->count();
    }

    /**
     * Get a single result from the query by ID.
     *
     * @param  int|string $id
     * @return \Illuminate\Database\Eloquent\Model|\stdClass|null
     */
    public function find(int|string $id): Model|stdClass|null
    {
        $this->prepareForFetch();

        return $this->query->find($id);
    }

    /**
     * Get the first result of the query.
     *
     * @return \Illuminate\Database\Eloquent\Model|\stdClass|null
     */
    public function first(): Model|stdClass|null
    {
        $this->prepareForFetch();

        return $this->query->first();
    }

    /**
     * Prepare the query to fetch the results.
     *
     * @return void
     */
    protected function prepareForFetch(): void
    {
        $this->applySearch();
        $this->applyFilters();
        $this->applySort();
        QueryHelper::preventAmbiguousQuery($this->query);
        $this->prepareSelect();
    }

    /**
     * Set select fields if not set.
     *
     * @return void
     */
    protected function prepareSelect(): void
    {
        $query = is_a($this->query, EloquentBuilder::class)
            ? $this->query->getQuery()
            : $this->query;

        if (empty($query->joins) || !empty($query->bindings['select'])) {
            return;
        }

        $table = QueryHelper::tableName($query);
        $selectStr = isset($table) ? "{$table}.*" : '*';
        $this->select($selectStr);
    }

    /**
     * Apply the search on the query.
     *
     * @return void
     */
    protected function applySearch(): void
    {
        if ($this->search) {
            $this->search->applyOn($this->query);
        }
    }

    /**
     * Apply the sorting on the query.
     *
     * @return void
     */
    protected function applySort(): void
    {
        if ($this->sort) {
            $this->sort->applyOn($this->query);
        }
    }

    /**
     * Apply the filters on the query.
     *
     * @return void
     */
    protected function applyFilters(): void
    {
        $filtersCount = count($this->filters);

        if ($filtersCount === 1) {
            $filter = $this->filters[0];
            $filter->applyOn($this->query);
        } elseif ($filtersCount > 1) {
            $this->query->where(function ($query) {
                foreach ($this->filters as $filter) {
                    $filter->applyOn($query);
                }
            });
        }
    }
}
