<?php

namespace App\Console\Commands\Traits;

trait GetsDefaultQueueName
{
    /**
     * Gets the default queue name.
     *
     * @return string|null
     */
    protected function getDefaultQueueName(): ?string
    {
        if ($connection = config('queue.default')) {
            return config("queue.connections.{$connection}.queue");
        } else {
            return null;
        }
    }
}
