<?php

namespace App\Repositories\V1\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array<\App\Repositories\V1\Filter|\App\Repositories\V1\FilterGroup> makeFilters(string $data, DataFieldPreparer|null $fieldPreparer = null) Make repository filters array from the valid filters data.
 * @method static \App\Repositories\V1\Search makeSearch(string $data, DataFieldPreparer|null $fieldPreparer = null) Make repository search class from the given valid search params data.
 * @method static \App\Repositories\V1\Sort makeSort(string $data, DataFieldPreparer|null $fieldPreparer = null) Make repository sort class from the given valid sorting params data.
 * @method static \App\Repositories\V1\Pagination makePagination(string $data, DataFieldPreparer|null $fieldPreparer = null) Make repository pagination class from the given valid pagination params data.
 *
 * @see \App\Repositories\V1\Helpers\RepositoryParams
 */
class RepositoryParams extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'repository-params';
    }
}
