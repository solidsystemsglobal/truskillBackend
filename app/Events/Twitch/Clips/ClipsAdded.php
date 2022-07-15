<?php

namespace App\Events\Twitch\Clips;

use Illuminate\Queue\SerializesModels;

class ClipsAdded extends ClipsEvent
{
    use SerializesModels;
}
