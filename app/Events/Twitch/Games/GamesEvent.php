<?php

namespace App\Events\Twitch\Games;

use App\Models\TwitchGame;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;

abstract class GamesEvent
{
    use Dispatchable;

    /**
     * @var \Illuminate\Database\Eloquent\Collection<\App\Models\TwitchGame>
     */
    public $games;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\TwitchGame|\Illuminate\Database\Eloquent\Collection<\App\Models\TwitchGame> $games
     * @return void
     */
    public function __construct(TwitchGame|Collection $games)
    {
        if ($games instanceof TwitchGame) {
            $this->games = new Collection($games);
        } else {
            $this->games = $games;
        }
    }
}
