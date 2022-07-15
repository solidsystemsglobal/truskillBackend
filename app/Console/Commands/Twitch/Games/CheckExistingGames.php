<?php

namespace App\Console\Commands\Twitch\Games;

use App\Models\TwitchGame;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Jobs\Twitch\Games\FixTwitchGames;
use Illuminate\Database\Eloquent\Collection;

class CheckExistingGames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:existing-games';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all existing games.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            TwitchGame::chunk(100, fn ($games) => $this->checkGames($games));
        } catch (\Exception $exp) {
            Log::error('Checking games failed.', ['exception' => $exp]);
        }
    }

    /**
     * Check 100 games.
     *
     * @param  \Illuminate\Database\Eloquent\Collection<\App\Models\TwitchGame> $games
     * @return void
     */
    protected function checkGames(Collection $games): void
    {
        FixTwitchGames::dispatch($games);
    }
}
