<?php

namespace App\Events\Twitch\Clips;

use App\Models\TwitchClip;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;

abstract class ClipsEvent
{
    use Dispatchable;

    /**
     * @var \Illuminate\Database\Eloquent\Collection<\App\Models\TwitchClip>
     */
    public $clips;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\TwitchClip|\Illuminate\Database\Eloquent\Collection<\App\Models\TwitchClip> $clips
     * @return void
     */
    public function __construct(TwitchClip|Collection $clips)
    {
        if ($clips instanceof TwitchClip) {
            $this->clips = new Collection($clips);
        } else {
            $this->clips = $clips;
        }
    }
}
