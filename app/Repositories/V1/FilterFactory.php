<?php

namespace App\Repositories\V1;

use App\Repositories\V1\Enums\FilterMode;

class FilterFactory
{
    /**
     * @var array<string>
     */
    protected array $registry = [
        FilterMode::IS_LIKE => Filters\IsLikeFilter::class,
        FilterMode::IS_NOT_LIKE => Filters\IsNotLikeFilter::class,
        FilterMode::IS_GREATER => Filters\IsGreaterFilter::class,
        FilterMode::IS_GREATER_OR_EQUAL => Filters\IsGreaterOrEqualFilter::class,
        FilterMode::IS_LOWER => Filters\IsLowerFilter::class,
        FilterMode::IS_LOWER_OR_EQUAL => Filters\IsLowerOrEqualFilter::class,
        FilterMode::IN_RANGE => Filters\InRangeFilter::class,
        FilterMode::NOT_IN_RANGE => Filters\NotInRangeFilter::class,
        FilterMode::EQUALS_TO => Filters\EqualsToFilter::class,
        FilterMode::NOT_EQUALS_TO => Filters\NotEqualsToFilter::class,
        FilterMode::INCLUDED_IN => Filters\IncludedInFilter::class,
        FilterMode::NOT_INCLUDED_IN => Filters\NotIncludedInFilter::class,
        FilterMode::CONTAINS => Filters\ContainsFilter::class,
        FilterMode::DOES_NOT_CONTAIN => Filters\DoesNotContainFilter::class,
        FilterMode::IS_NULL => Filters\IsNullFilter::class,
        FilterMode::IS_NOT_NULL => Filters\IsNotNullFilter::class,
    ];

    /**
     * Creates the corresponding filter instance according to the given parameters.
     *
     * @param  string $mode
     * @param  string|null $field
     * @param  mixed $value
     * @param  bool $orCond
     * @return \App\Repositories\V1\Filter
     * @throws \Exception
     */
    public function create(string $mode, ?string $field, mixed $value, bool $orCond): Filter
    {
        $class = $this->getClass($mode);

        return $class::make($field, $value, $orCond);
    }

    /**
     * Returns the corresponding filter's class name.
     *
     * @param  string $mode
     * @return string
     * @throws \Exception
     */
    public function getClass(string $mode): string
    {
        if (!$this->isRegisteredMode($mode)) {
            throw new \Exception(trans('exceptions.undefined_repository_filter'));
        }

        return $this->registry[$mode];
    }

    /**
     * Determines if the given filter mode is registered or not.
     *
     * @param  string $mode
     * @return bool
     */
    public function isRegisteredMode(string $mode): bool
    {
        return isset($this->registry[$mode]);
    }

    /**
     * Registers a new filter mode.
     *
     * @param  string $mode
     * @param  string $class
     * @return void
     */
    public function register(string $mode, string $class): void
    {
        $this->registry[$mode] = $class;
    }
}
