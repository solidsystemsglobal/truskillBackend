<?php

namespace App\Http\Resources\Api;

use App\Repositories\V1\DataFieldPreparer;

class ResourceFieldPreparerContainer
{
    /**
     * Resolves the resource field preparer instance.
     *
     * @param  string $resourceClass
     * @return \App\Repositories\V1\DataFieldPreparer
     * @throws \Exception
     */
    public static function resolve(string $resourceClass): DataFieldPreparer
    {
        $preparerClass = match ($resourceClass) {
            // AnyResource::class => AnyPreparer::class,
            default => DataFieldPreparer::class,
        };

        return $preparerClass::make($resourceClass::fieldMap());
    }
}
