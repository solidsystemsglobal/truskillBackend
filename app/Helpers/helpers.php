<?php

use GuzzleHttp\Client;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * Checks if a date string is given and converts it to the standard date format.
 * Otherwise, return the same value as it was given.
 *
 * @param  string $value
 * @return string
 */
function parseDateString(string $value): string
{
    // Prevent phone and timestamp-like numbers to be converted into dates
    if (preg_match('/^\+?\d+$/', $value)) {
        return $value;
    }

    $validator = Validator::make(['value' => $value], ['value' => 'required|date']);

    if (!$validator->fails()) {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    return $value;
}

/**
 * Rounds up the numeric value to the given precision.
 *
 * @param  mixed $value
 * @param  mixed $precision
 * @return float
 */
function roundUp($value, $precision)
{
    $pow = pow(10, $precision);

    return (ceil($pow * $value) + ceil($pow * $value - ceil($pow * $value))) / $pow;
}

/**
 * Converts the value to decimal if not null.
 *
 * @param  mixed $value
 * @param  int|null $precision
 * @return float|null
 */
function toDecimal($value, int $precision = null)
{
    return is_null($precision)
        ? (!is_null($value) ? (float) $value : null)
        : round($value, $precision);
}

/**
 * Converts the value to money format if not null.
 *
 * @param  mixed $value
 * @return float|null
 */
function toMoney($value)
{
    return toDecimal($value, 2);
}

/**
 * Convert the value to date time if not null.
 *
 * @param  mixed $value
 * @return string
 */
function toDateTime($value)
{
    if (!is_null($value)) {
        if (!is_a($value, Carbon::class)) {
            $value = Carbon::parse($value);
        }

        return $value->format(config('env.DATETIME_FORMAT'));
    } else {
        return null;
    }
}

/**
 * Convert the value to date if not null.
 *
 * @param  mixed $value
 * @return string|null
 */
function toDate($value)
{
    if (!is_null($value)) {
        if (!is_a($value, Carbon::class)) {
            $value = Carbon::parse($value);
        }

        return $value->format(config('env.DATE_FORMAT'));
    } else {
        return null;
    }
}

/**
 * Checks if the given array contains only values of the given type.
 *
 * @param  array $array
 * @param  string $type
 * @return bool
 */
function isArrayOf(array $array, string $type)
{
    if (function_exists('is_' . $type)) {
        $typeChecker = 'is_' . $type;

        foreach ($array as $elm) {
            if (!$typeChecker($elm)) {
                return false;
            }
        }
    } elseif (class_exists($type)) {
        foreach ($array as $elm) {
            if (!is_a($elm, $type)) {
                return false;
            }
        }
    } else {
        return false;
    }

    return true;
}

/**
 * Generates a google maps URL from the given coordinates.
 *
 * @param  float $lat
 * @param  float $long
 * @return string
 */
function generateGoogleMapsUrl(float $lat, float $long): string
{
    return "https://www.google.com/maps/search/?api=1&query={$lat},{$long}";
}

/**
 * Sanitizes boolean value.
 *
 * @param  mixed $value
 * @return bool
 */
function sanitizeBoolean($value)
{
    return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
}

/**
 * Sanitizes phone number string (E.164).
 *
 * @param  string|null $value
 * @return string|null
 */
function sanitizePhoneNumber(string $value = null): ?string
{
    if (!$value) {
        return $value;
    }

    $value = preg_replace('/[^\d]/', '', $value);

    if (substr($value, 0, 2) === '00') {
        $value = '+' . substr($value, 2);
    } elseif (@$value[0] !== '+') {
        $value = '+' . $value;
    }

    return $value;
}

/**
 * Get object field value.
 *
 * @param  object $object
 * @param  string ...$fields
 * @return mixed
 */
function getObjField(object $object, string ...$fields)
{
    if ($fields) {
        $value = $object;

        foreach ($fields as $field) {
            if (is_object($value) && isset($value->{$field})) {
                $value = $value->{$field};
            } else {
                return null;
            }
        }

        return $value;
    } else {
        return $object;
    }
}

/**
 * Get file contents using it's URL.
 *
 * @param  string $url
 * @return string|false
 */
function getFileContents(string $url)
{
    try {
        $httpClient = new Client();
        $response = $httpClient->request('GET', $url);
        $contents = $response->getBody()->getContents();

        return $contents;
    } catch (\Exception $exp) {
        return false;
    }
}

/**
 * Get file mime type using it's URL.
 *
 * @param  string $url
 * @return string|null
 */
function getFileMime(string $url): ?string
{
    try {
        $httpClient = new Client();
        $response = $httpClient->request('HEAD', $url);
        $contentType = $response->getHeaderLine('content-type');

        return $contentType ?: null;
    } catch (\Exception $exp) {
        return null;
    }
}

/**
 * Throw an exception that the given resource has attached relations.
 *
 * @param  string $resource
 * @param  string $relation
 * @return void
 * @throws \Exception
 */
function throwHasAttachedRelationsException(string $resource, string $relation)
{
    throw new \Exception(
        trans('exceptions.linked_relation_exist', compact('resource', 'relation'))
    );
}

/**
 * Throw an exception that the given resource not found.
 *
 * @param  string $attr
 * @return void
 * @throws \Exception
 */
function throwNotFoundException(string $attr)
{
    throw new \Exception(trans('api_exceptions.resource_not_found', compact('attr')));
}

/**
 * Enables query log.
 *
 * @return void
 */
function enableQueryLog()
{
    DB::enableQueryLog();
}

/**
 * Dumps query log.
 *
 * @return void
 */
function dumpQueryLog()
{
    dd(DB::getQueryLog());
}

/**
 * Get safe quoted query expression of the given column.
 *
 * @param  string $column
 * @return \Illuminate\Database\Query\Expression
 */
function dbRawColumn(string $column)
{
    $column = explode('.', $column);

    foreach ($column as $i => $part) {
        $column[$i] = "`{$part}`";
    }

    $safeCol = join('.', $column);

    return DB::raw($safeCol);
}

/**
 * Returns asset from swagger-ui composer package.
 *
 * @param  string  $documentation
 * @param $asset string
 * @return string
 */
function secure_l5_swagger_asset(string $documentation, $asset)
{
    resolve(\Illuminate\Routing\UrlGenerator::class)->forceScheme('https');

    return l5_swagger_asset($documentation, $asset);
}
