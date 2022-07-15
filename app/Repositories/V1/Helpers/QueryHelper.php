<?php

namespace App\Repositories\V1\Helpers;

use Illuminate\Database\Query\JoinClause;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class QueryHelper
{
    /**
     * Prevents an ambiguous query execution by adding the main table name to
     * the column names in the query where no table name is specified.
     * For now, checks only order and where clauses.
     *
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    public function preventAmbiguousQuery(QueryBuilder|EloquentBuilder $query): void
    {
        if (is_a($query, EloquentBuilder::class)) {
            $query = $query->getQuery();
        }

        if (empty($query->joins)) {
            return;
        }

        $table = $this->tableName($query);
        $this->addTableNameToWheres($query, $table);
        $this->addTableNameToOrders($query, $table);
    }

    /**
     * Returns the query table name.
     *
     * @param  QueryBuilder|EloquentBuilder|JoinClause $query
     * @return string
     */
    public function tableName(QueryBuilder|EloquentBuilder|JoinClause $query): ?string
    {
        if (is_a($query, EloquentBuilder::class)) {
            return $query->getModel()->getTable();
        }

        $table = $query->from ?? $query->table;

        if (!is_string($table)) {
            $table = (string) $table;
        }

        // Turns "original_table as name_to_use" to "name_to_use"
        if (str_contains($table, ' ')) {
            $lastSpacePos = strrpos($table, ' ');
            $table = substr($table, $lastSpacePos + 1);
        }

        return $table;
    }

    /**
     * Add table name to the generally stated order clause column names.
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @param  string $table
     * @return void
     */
    protected function addTableNameToOrders(QueryBuilder $query, string $table): void
    {
        if (!$query->orders) {
            return;
        }

        foreach ($query->orders as $i => $item) {
            if (isset($item['column']) && !str_contains($item['column'], '.')) {
                $query->orders[$i]['column'] = $table . '.' . $item['column'];
            } elseif (isset($item['query']) && is_a($item['query'], QueryBuilder::class)) {
                $table = $this->tableName($item['query']);
                $this->addTableNameToOrders($item['query'], $table);
            }
        }
    }

    /**
     * Add table name to the generally stated where clause column names.
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @param  string $table
     * @return void
     */
    protected function addTableNameToWheres(QueryBuilder $query, string $table): void
    {
        if (!$query->wheres) {
            return;
        }

        foreach ($query->wheres as $i => $item) {
            if (isset($item['column']) && !str_contains($item['column'], '.')) {
                $query->wheres[$i]['column'] = $table . '.' . $item['column'];
            } elseif (isset($item['query']) && is_a($item['query'], QueryBuilder::class)) {
                $this->addTableNameToWheres(
                    $item['query'],
                    $this->tableName($item['query'])
                );
            }
        }
    }
}
