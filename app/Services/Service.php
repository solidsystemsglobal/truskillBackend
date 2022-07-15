<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class Service
{
    /**
     * Begin a transaction.
     *
     * @return void
     */
    public function startTransaction(): void
    {
        DB::beginTransaction();
    }

    /**
     * Commit a transaction.
     *
     * @return void
     */
    public function commit(): void
    {
        DB::commit();
    }

    /**
     * Rollback the transaction.
     *
     * @return void
     */
    public function rollback(): void
    {
        DB::rollBack();
    }
}
