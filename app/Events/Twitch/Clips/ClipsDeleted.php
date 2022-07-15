<?php

namespace App\Events\Twitch\Clips;

use Illuminate\Queue\SerializesModels;

class ClipsDeleted extends ClipsEvent
{
    use SerializesModels;
}
