<?php

namespace App\Events\Twitch\Games;

use Illuminate\Queue\SerializesModels;

class GamesAdded extends GamesEvent
{
    use SerializesModels;
}
