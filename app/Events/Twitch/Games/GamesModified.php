<?php

namespace App\Events\Twitch\Games;

use Illuminate\Queue\SerializesModels;

class GamesModified extends GamesEvent
{
    use SerializesModels;
}
