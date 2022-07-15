<?php

namespace App\Repositories\V1;

/**
 * Class used to describe repository pagination parameters.
 */
class Pagination
{
    /**
     * Makes a new instance of this class.
     *
     * @param  int $perPage
     * @param  int $page
     * @return static
     */
    public static function make(int $perPage, int $page): static
    {
        return new static($perPage, $page);
    }

    /**
     * Constructor.
     *
     * @param  int $perPage
     * @param  int $page
     * @return void
     */
    public function __construct(
        public int $perPage,
        public int $page
    ) {
        //
    }
}
