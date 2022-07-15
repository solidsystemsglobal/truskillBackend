<?php

namespace App\Listeners\Twitch\Games;

use App\Events\Twitch\Games\GamesEvent;
use App\Jobs\Twitch\Games\FixTwitchGames;
use Illuminate\Contracts\Queue\ShouldQueue;

class FixAddedGames implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\Twitch\Games\GamesEvent $event
     * @return void
     */
    public function handle(GamesEvent $event)
    {
        FixTwitchGames::dispatch($event->games);
    }
}
