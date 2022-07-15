<?php

namespace App\Http\Controllers\Api;

use App\Repositories\V1\Sort;
use App\Rules\RepositorySort;
use App\Repositories\V1\Search;
use App\Rules\RepositorySearch;
use App\Exceptions\ApiException;
use App\Rules\RepositoryFilters;
use Illuminate\Support\Collection;
use App\Repositories\V1\Pagination;
use App\Repositories\V1\Repository;
use App\Rules\RepositoryPagination;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use App\Repositories\V1\DataFieldPreparer;
use Illuminate\Contracts\Pagination\Paginator;
use App\Repositories\V1\Facades\RepositoryParams;
use Illuminate\Database\Query\Builder as QueryBuilder;
use \App\Http\Controllers\Controller as BaseController;
use App\Http\Resources\Api\ResourceFieldPreparerContainer;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class Controller extends BaseController
{
    /**
     * @var string|null
     */
    protected ?string $repositoryClass = Repository::class;

    /**
     * @var \App\Repositories\V1\DataFieldPreparer
     */
    protected ?DataFieldPreparer $dataFieldPreparer = null;

    /**
     * Run the route middleware, than get the service instance.
     *
     * @param  string $className
     * @param  string $varName
     * @return void
     */
    protected function initService(string $className, string $varName)
    {
        $this->middleware(function ($request, $next) use ($className, $varName) {
            $this->{$varName} = resolve($className);

            return $next($request);
        });
    }

    /**
     * Get data from the given query and the parameters from the request.
     *
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @param  bool $requirePagination
     * @param  bool $setRequestParams
     * @return \Illuminate\Contracts\Pagination\Paginator|\Illuminate\Support\Collection
     * @throws \Illuminate\Validation\ValidationException|\App\Exceptions\ApiException
     */
    protected function fetchData(
        QueryBuilder|EloquentBuilder $query,
        bool $requirePagination = true,
        bool $setRequestParams = true,
        ?string $resourceClass = null
    ): Paginator|Collection {
        if ($resourceClass) {
            if (is_a($query, EloquentBuilder::class)) {
                $resourceClass::eagerLoadRelations($query);
            }

            $fieldPreparer = ResourceFieldPreparerContainer::resolve($resourceClass);
            $this->dataFieldPreparer = $fieldPreparer;
        }

        $dataSource = $this->dataSource($query, $setRequestParams);
        $pagination = $this->getRequestPagination($requirePagination);

        try {
            $results = !is_null($pagination)
                ? $dataSource->paginate($pagination)
                : $dataSource->get();
        } catch (\Exception $exp) {
            throw new ApiException(
                ApiException::FETCH_FAILED,
                'resources.data',
                $exp->getMessage()
            );
        }

        return $results;
    }

    /**
     * Makes a data source with the given query and the parameters from the request.
     *
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @param  bool $setRequestParams
     * @return \App\Repositories\V1\Repository
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function dataSource(
        QueryBuilder|EloquentBuilder $query,
        bool $setRequestParams = true
    ): Repository {
        $dataSource = $this->repositoryClass::make($query);

        if ($setRequestParams) {
            if ($sort = $this->getRequestSorting()) {
                $dataSource->sort($sort);
            }

            if ($search = $this->getRequestSearch()) {
                $dataSource->search($search);
            }

            if ($filters = $this->getRequestFilters()) {
                $dataSource->filter(...$filters);
            }
        }

        return $dataSource;
    }

    /**
     * Get pagination parameters from the request.
     *
     * @param  bool $require
     * @return \App\Repositories\V1\Pagination|null
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function getRequestPagination(bool $require = false): ?Pagination
    {
        $requireOrNull = $require ? 'required' : 'nullable';
        $validator = Validator::make(
            ['pagination' => Request::input('pagination')],
            ['pagination' => [$requireOrNull, new RepositoryPagination()]]
        );

        $data = $validator->validate();
        $data = $data['pagination'];

        return $data ? RepositoryParams::makePagination($data) : null;
    }

    /**
     * Get sorting parameters from the request.
     *
     * @return \App\Repositories\V1\Sort|null
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function getRequestSorting(): ?Sort
    {
        $validator = Validator::make(
            ['sort' => Request::input('sort')],
            ['sort' => ['nullable', new RepositorySort()]]
        );

        $data = $validator->validate();

        return $data['sort']
            ? RepositoryParams::makeSort($data['sort'], $this->dataFieldPreparer)
            : null;
    }

    /**
     * Get searching parameters from the request.
     *
     * @return \App\Repositories\V1\Search|null
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function getRequestSearch(): ?Search
    {
        $validator = Validator::make(
            ['search' => Request::input('search')],
            ['search' => ['nullable', new RepositorySearch()]]
        );

        $data = $validator->validate();

        return $data['search']
            ? RepositoryParams::makeSearch($data['search'], $this->dataFieldPreparer)
            : null;
    }

    /**
     * Get filtering parameters from the request.
     *
     * @return array<\App\Repositories\V1\FilterGroup|\App\Repositories\V1\Filter>
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function getRequestFilters(): array
    {
        $validator = Validator::make(
            ['filters' => Request::input('filters')],
            ['filters' => ['nullable', new RepositoryFilters()]]
        );

        $data = $validator->validate();

        return $data['filters']
            ? RepositoryParams::makeFilters($data['filters'], $this->dataFieldPreparer)
            : [];
    }

    /**
     * Sends json response.
     *
     * @param  mixed $data  Response data
     * @param  int   $code  Response status code, 200 by default
     * @return mixed
     */
    public function response($data = [], int $code = 200)
    {
        if (is_a($data, JsonResource::class) || is_a($data, ResourceCollection::class)) {
            return $data->response()->setStatusCode($code);
        }

        return response()->json($data, $code);
    }
}
