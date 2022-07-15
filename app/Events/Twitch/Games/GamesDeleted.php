<?php

namespace App\Events\Twitch\Games;

use Illuminate\Queue\SerializesModels;

class GamesDeleted extends GamesEvent
{
    use SerializesModels;
}
