<?php

namespace App\Console\Commands\Twitch\Clips;

use App\Models\TwitchGame;
use App\Services\Twitch\TwitchClipService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Jobs\Twitch\Clips\GetTwitchClips;
use romanzipp\Twitch\Twitch;

class CheckTwitchApiForNewClips extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:api-for-new-clips';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Twitch for any new clip.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    /**
     * The Twitch api.
     *
     * @var \romanzipp\Twitch\Twitch
     */
    protected $twitch;

    /**
     * The Twitch game.
     *
     * @var \App\Models\TwitchGame
     */
    protected $game;

    /**
     * The parameters.
     *
     * @var array
     */
    protected $parameters;

    public function handle()
    {
        $start = microtime(true);

        try {
            TwitchGame::lazyById(100)->each(fn($game) => $this->getClips($game));

            Log::info('End Jobs', ['game_id' => $this->game->twitch_id, 'duration' => microtime(true) - $start, 'end_at' => microtime(true)]);

        } catch (\Exception $exp) {
            Log::error('Getting clips failed', ['exception' => $exp]);
        }

        Log::info('End Execution to getting game clips', ['start_at' => $start, 'duration' => microtime(true) - $start]);
    }

    protected function getClips($game)
    {
        $start = microtime(true);
        $this->twitch = new Twitch();

        $this->parameters = [
            'first' => 10,
            'started_at' => Carbon::now()->subWeek()->format('Y-m-d\TH:i:s\Z'),
            'game_id' => $game->twitch_id,
        ];

        $response = $this->twitch->getClips($this->parameters);
        $clips = $response->data();

        resolve(TwitchClipService::class)->createMany($game, (array) $clips);

        Log::info('End Jobs', ['game_id' => $game->twitch_id, 'duration' => microtime(true) - $start, 'end_at' => microtime(true)]);

    }

    /**
     * Get 10 top clips for last 7 days.
     *
     * @param  \App\Models\TwitchGame $game
     * @return void
     */
//    protected function getClips(TwitchGame $game): void
//    {
//        GetTwitchClips::dispatch($game);
//    }
}
