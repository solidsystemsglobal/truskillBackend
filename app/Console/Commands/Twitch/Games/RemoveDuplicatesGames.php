<?php

namespace App\Console\Commands\Twitch\Games;

use App\Models\TwitchGame;
use Illuminate\Console\Command;

class RemoveDuplicatesGames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'games:remove-duplicates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all duplicate games.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        TwitchGame::chunk(100, function($games) {
            foreach($games as $game) {
                TwitchGame::where('twitch_id', $game->twitch_id)->where('id', '!=', $game->id)->delete();
            }
        });
    }
}
