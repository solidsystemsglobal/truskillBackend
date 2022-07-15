<?php

namespace App\Events\Twitch\Clips;

use Illuminate\Queue\SerializesModels;

class ClipsModified extends ClipsEvent
{
    use SerializesModels;
}
