<?php

namespace App\Repositories\V1\Helpers;

use App\Repositories\V1\Sort;
use App\Rules\RepositorySort;
use App\Repositories\V1\Filter;
use App\Repositories\V1\Search;
use App\Rules\RepositorySearch;
use App\Rules\RepositoryFilters;
use App\Repositories\V1\Pagination;
use App\Rules\RepositoryPagination;
use App\Repositories\V1\FilterGroup;
use App\Repositories\V1\DataFieldPreparer;
use App\Repositories\V1\Facades\FilterFactory;
use App\Repositories\V1\Facades\FilterOptimizer;

class RepositoryParams
{
    /**
     * Make repository filters array from the valid filters data.
     *
     * @param  string $data
     * @param  \App\Repositories\V1\DataFieldPreparer|null $fieldPreparer
     * @return array<\App\Repositories\V1\Filter|\App\Repositories\V1\FilterGroup>
     */
    public function makeFilters(string $data, ?DataFieldPreparer $fieldPreparer = null): array
    {
        $data = RepositoryFilters::parse($data);
        $fieldPreparer ??= DataFieldPreparer::make();

        foreach ($data as $i => $item) {
            $data[$i] = $this->makeFilter($item, $fieldPreparer);
        }

        return FilterOptimizer::optimize($data);
    }

    /**
     * Make repository search class from the given valid search params data.
     *
     * @param  string $data
     * @param  \App\Repositories\V1\DataFieldPreparer|null $fieldPreparer
     * @return \App\Repositories\V1\Search
     */
    public function makeSearch(string $data, ?DataFieldPreparer $fieldPreparer = null): Search
    {
        $params = RepositorySearch::parse($data);
        $fieldPreparer ??= DataFieldPreparer::make();
        $text = $params[0];
        $fields = $params[1];

        foreach ($fields as $i => $field) {
            $fields[$i] = $fieldPreparer->prepare($field);
        }

        return Search::make($text, ...$fields);
    }

    /**
     * Make repository sort class from the given valid sorting params data.
     *
     * @param  string $data
     * @param  \App\Repositories\V1\DataFieldPreparer|null $fieldPreparer
     * @return \App\Repositories\V1\Sort
     */
    public function makeSort(string $data, ?DataFieldPreparer $fieldPreparer = null): Sort
    {
        $params = RepositorySort::parse($data);
        $fieldPreparer ??= DataFieldPreparer::make();
        $field = $fieldPreparer->prepare($params[0]);
        $dir = $params[1];

        return Sort::make($field, $dir);
    }

    /**
     * Make repository pagination class from the given valid pagination params data.
     *
     * @param  string $data
     * @return \App\Repositories\V1\Pagination
     */
    public function makePagination(string $data): Pagination
    {
        $params = RepositoryPagination::parse($data);
        $page = $params[0];
        $perPage = $params[1];

        return Pagination::make($perPage, $page);
    }

    /**
     * Make repository filter from the given valid filter data.
     *
     * @param  array $data
     * @param  \App\Repositories\V1\DataFieldPreparer $fieldPreparer
     * @return \App\Repositories\V1\Filter|\App\Repositories\V1\FilterGroup
     */
    protected function makeFilter(array $data, DataFieldPreparer $fieldPreparer): Filter|FilterGroup
    {
        $relation = $data['relation'] ?? null;
        $orCond = $data['orCond'] ?? false;

        if (isset($data['items'])) {
            $group = FilterGroup::make($relation, $orCond);

            foreach ($data['items'] as $item) {
                $item = $this->makeFilter($item, $fieldPreparer);
                $group[] = $item;
            }

            return $group;
        } else {
            $mode = $data['mode'];
            $value = $data['value'] ?? null;
            $field = isset($data['field'])
                ? $fieldPreparer->prepare($data['field'])
                : null;

            return FilterFactory::create($mode, $field, $value, $orCond);
        }
    }
}
