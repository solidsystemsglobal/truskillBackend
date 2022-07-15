<?php

namespace App\Jobs\Twitch\Games;

use App\Models\TwitchGame;
use romanzipp\Twitch\Twitch;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Database\Eloquent\Collection;
use App\Jobs\Twitch\Clips\GetTwitchClipsMany;

class FixTwitchGames implements ShouldQueue
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
     * The Twitch api.
     *
     * @var \romanzipp\Twitch\Twitch
     */
    protected $twitch;

    /**
     * Create a new job instance.
     *
     * @param \Illuminate\Database\Eloquent\Collection<\App\Models\TwitchGame> $games
     * @return void
     */
    public function __construct(Collection $games)
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
        $this->twitch = new Twitch();
        $savedIds = [];
        $checkedIds = $this->games->pluck('id')->toArray();
        $parameters = ['name' => $this->games->pluck('name')->toArray()];
        $apiResponse = $this->twitch->getGames($parameters);

        if ($apiResponse->getStatus() !== '200') {
            foreach ($apiResponse->data() as $gameData) {
                TwitchGame::where('name', $gameData->name)->update([
                    'twitch_id' => $gameData->id,
                    'box_art_url' => $gameData->box_art_url,
                ]);

                $savedIds[] = $gameData->id;
            }

            $newGames = TwitchGame::whereIn('twitch_id', $savedIds)->get();
            $deleteIds = TwitchGame::whereIn('id', $checkedIds)->whereNotIn('twitch_id', $savedIds)->pluck('id')->toArray();
            TwitchGame::whereIn('id', $deleteIds)->delete();
            Log::info("Twitch api games checking.", ['Checked game ids:' => implode(',', $checkedIds), 'Deleted game ids:' => implode(',', $deleteIds)]);
        } else {
            Log::warning(
                "Twitch API response error.",
                [
                    'status code' => $apiResponse->getStatus(),
                    'Error msg' => $apiResponse->getErrorMessage(),
                ]
            );
        }

        // GetTwitchClipsMany::dispatch($newGames);
    }
}
