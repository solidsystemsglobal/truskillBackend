<?php

namespace App\Repositories\V1\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void preventAmbiguousQuery(Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query)  Prevents an ambiguous query execution.
 * @method static string tableName(Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder||\Illuminate\Database\Query\JoinClause $query)  Returns the query table name.
 *
 * @see \App\Repositories\V1\Helpers\QueryHelper
 */
class QueryHelper extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'repository-query-helper';
    }
}
