<?php

namespace App\Jobs\Twitch\Clips;

use romanzipp\Twitch\Twitch;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use App\Services\Twitch\TwitchGameService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CheckTopGamesForNew implements ShouldQueue
{
    use Queueable;
    use Dispatchable;
    use SerializesModels;

    /**
     * The Twitch api.
     *
     * @var \romanzipp\Twitch\Twitch
     */
    protected $twitch;

    /**
     * The current cursor.
     *
     * @var string
     */
    protected $cursor;

    /**
     * Create a new job instance.
     *
     * @param  string|null $cursor
     * @return void
     */
    public function __construct($cursor = null)
    {
        $this->cursor = $cursor;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $start = microtime(true);
        $this->twitch = new Twitch();
        $this->parameters = [
            'first' => 10,
            'started_at' => Carbon::now()->subWeek()->format('Y-m-d\TH:i:s\Z'),
            'game_id' => $this->game->twitch_id,
        ];

        $response = $this->twitch->getClips($this->parameters);
        $clips = $response->data();

        resolve(TwitchGameService::class)->createMany($this->game, $clips);

        Log::info('End Jobs', ['game_id' => $this->game->twitch_id, 'duration' => microtime(true) - $start, 'end_at' => microtime(true)]);
    }
}
