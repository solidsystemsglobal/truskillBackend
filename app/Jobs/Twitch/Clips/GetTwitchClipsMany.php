<?php

namespace App\Jobs\Twitch\Clips;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GetTwitchClipsMany implements ShouldQueue
{
    use Queueable;
    use Dispatchable;
    use SerializesModels;

    /**
     * The games.
     *
     * @var \Illuminate\Database\Eloquent\Collection<\App\Models\TwitchGame>
     */
    protected $games;

    /**
     * Create a new job instance.
     *
     * @param  \Illuminate\Database\Eloquent\Collection<\App\Models\TwitchGame> $game
     * @return void
     */
    public function __construct($games)
    {
        $this->games = $games;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->games as $game) {
            GetTwitchClips::dispatch($game);
        }
    }
}
