<?php

namespace App\Repositories\V1;

class DataFieldMap
{
    /**
     * @var array
     */
    protected array $map = [];

    /**
     * Makes an instance of this class.
     *
     * @return static
     */
    public static function make(): static
    {
        return new static();
    }

    /**
     * Set a public field name to a data original field name relation.
     *
     * @param  string $publicName
     * @param  string $originalName
     * @return static
     */
    public function set(string $publicName, string $originalName): static
    {
        $this->map[$publicName] = $originalName;

        return $this;
    }

    /**
     * Add resource field to data field relation.
     *
     * @param  \App\Repositories\V1\DataFieldMap|null $map
     * @param  string|null $prefix
     * @return static
     */
    public function merge(?DataFieldMap $map, ?string $prefix = null): static
    {
        if ($map) {
            $map = $map->all();
            $prefix = $prefix ? "{$prefix}." : '';

            foreach ($map as $publicName => $originalName) {
                $publicName = $prefix . $publicName;
                $this->map[$publicName] = $originalName;
            }
        }

        return $this;
    }

    /**
     * Returns the corresponding data field name for the given public name.
     *
     * @param  string $publicName
     * @return string
     */
    public function match(string $publicName): string
    {
        return $this->findMatch($publicName) ?? $publicName;
    }

    /**
     * Returns the field names map.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->map;
    }

    /**
     * Finds a match for the given public field name.
     *
     * @param  string $publicName
     * @return string|null
     */
    protected function findMatch(string $publicName): ?string
    {
        if (isset($this->map[$publicName])) {
            return $this->map[$publicName];
        } elseif (str_contains($publicName, '.')) {
            $lastDotPos = strrpos($publicName, '.');
            $relation = substr($publicName, 0, $lastDotPos);
            $field = substr($publicName, $lastDotPos + 1);

            if ($match = $this->findMatch($relation)) {
                return $match . '.' . $field;
            }
        }

        return null;
    }
}
