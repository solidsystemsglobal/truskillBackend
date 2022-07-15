<?php

namespace App\Http\Resources\Api;

use Illuminate\Support\Carbon;
use App\Repositories\V1\DataFieldMap;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource as BaseJsonResource;

class JsonResource extends BaseJsonResource
{
    /**
     * Returns the resource field name to data field name map.
     *
     * @return \App\Repositories\V1\DataFieldMap|null
     */
    public static function fieldMap(): ?DataFieldMap
    {
        return null;
    }

    /**
     * Eager loads relations used in the resource.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    final public static function eagerLoadRelations(Builder $query): void
    {
        $usedRelations = static::usedRelations();

        foreach ($usedRelations as $rel => $res) {
            if (is_int($rel)) {
                $query->with($res);
            } else {
                $query->with(
                    $rel,
                    fn($q) => $res::eagerLoadRelations($q->getQuery())
                );
            }
        }

        if ($countArgs = static::usedRelationCounts()) {
            $query->withCount($countArgs);
        }
    }

    /**
     * Returns the relations that are used in the resource.
     *
     * @return array
     */
    public static function usedRelations(): array
    {
        return [];
    }

    /**
     * Returns the relation counts that are used in the resource.
     *
     * @return array
     */
    public static function usedRelationCounts(): array
    {
        return [];
    }

    /**
     * Convert the value to decimal if not null.
     *
     * @param  mixed $value
     * @param  int|null $precision
     * @return float|null
     */
    public function toDecimal($value, $precision = null)
    {
        return toDecimal($value, $precision);
    }

    /**
     * Convert the value to money format if not null.
     *
     * @param  mixed $value
     * @return float|null
     */
    public function toMoney($value)
    {
        return toMoney($value);
    }

    /**
     * Convert the value to boolean if not null.
     *
     * @param  mixed $value
     * @return bool|null
     */
    public function toBool($value)
    {
        return !is_null($value) ? (bool) $value : null;
    }

    /**
     * Convert the value to a human readable date format if not null.
     *
     * @param  mixed $value
     * @return string|null
     */
    public function toHumanDate($value)
    {
        if (!is_null($value)) {
            if (!is_a($value, Carbon::class)) {
                $value = Carbon::parse($value);
            }

            return $value->diffForHumans();
        } else {
            return null;
        }
    }

    /**
     * Convert the value to date time if not null.
     *
     * @param  mixed $value
     * @return string|null
     */
    public function toDateTime($value)
    {
        return toDateTime($value);
    }

    /**
     * Convert the value to date if not null.
     *
     * @param  mixed $value
     * @return string|null
     */
    public function toDate($value)
    {
        return toDate($value);
    }

    /**
     * Convert the value to short time if not null.
     *
     * @param  mixed $value
     * @return string|null
     */
    public function toShortTime($value)
    {
        if (!is_null($value)) {
            if (!is_a($value, Carbon::class)) {
                $value = Carbon::parse($value);
            }

            return $value->format(config('env.TIME_FORMAT'));
        } else {
            return null;
        }
    }
}
