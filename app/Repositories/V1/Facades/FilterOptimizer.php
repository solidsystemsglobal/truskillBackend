<?php

namespace App\Repositories\V1\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array|\App\Repositories\V1\FilterGroup optimize(array<\App\Repositories\V1\Filter|\App\Repositories\V1\FilterGroup>|\App\Repositories\V1\FilterGroup $items) Optimizes the given filters.
 *
 * @see \App\Repositories\V1\Helpers\FilterOptimizer
 */
class FilterOptimizer extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'repository-filter-optimizer';
    }
}
