<?php

namespace App\Repositories\V1\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Repositories\V1\Filter create(string|null $mode, string $field, mixed $value, bool $orCond)  Creates the corresponding filter instance according to the given parameters.
 * @method static string getClass(string $mode): string  Returns the corresponding filter's class name.
 * @method static bool isRegisteredMode(string $mode)  Determines if the given filter mode is registered or not.
 * @method static void register(string $mode, string $class)  Registers a new filter mode.
 *
 * @see \App\Repositories\V1\FilterFactory
 */
class FilterFactory extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'repository-filter-factory';
    }
}
