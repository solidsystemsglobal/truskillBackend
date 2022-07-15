<?php

namespace App\Listeners\Twitch\Games;

use App\Events\Twitch\Games\GamesEvent;
use App\Jobs\Twitch\Clips\GetTwitchClips;
use Illuminate\Contracts\Queue\ShouldQueue;

class GetAddedGamesClips implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\Twitch\Games\GamesEvent $event
     * @return void
     */
    public function handle(GamesEvent $event)
    {
        foreach($event->games as $game) {
            GetTwitchClips::dispatch($game);
        }
    }
}
