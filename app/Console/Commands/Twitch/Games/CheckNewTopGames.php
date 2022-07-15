<?php

namespace App\Console\Commands\Twitch\Games;

use App\Models\TwitchGame;
use romanzipp\Twitch\Twitch;
use Illuminate\Console\Command;
use App\Services\Twitch\TwitchGameService;
use App\Console\Commands\Traits\GetsDefaultQueueName;

class CheckNewTopGames extends Command
{
    use GetsDefaultQueueName;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:for-new-games';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for new top games.';

    /**
     * The Twitch api.
     *
     * @var \romanzipp\Twitch\Twitch
     */
    protected $twitch;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->twitch = new Twitch();

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $next = true;
        $after = false;
        $index = 0;
        $gl_time_exec = 0;
        $parameters = ['first' => 100];
        $defaultQueue = $this->getDefaultQueueName();

        while ($next) {
            $time_start = microtime(true);
            if ($after) {
                $parameters['after'] = $after;
            }

            $currentResponse = $this->twitch->getTopGames($parameters);
            $games = $currentResponse->data();
            $gamesData = [];

             foreach ($games as $game) {

                $index++;
                $this->info("#$index Send to store if not exists game $game->name");

                $exists = TwitchGame::where('twitch_id', $game->id)->first();
                if (!$exists) {
                    $gamesData[] = [
                        'twitch_id' => $game->id,
                        'name' => $game->name,
                        'box_art_url' => $game->box_art_url,
                    ];
                } else  {
                    $exists->touch();
                }
            }
            (new TwitchGameService())->createMany($gamesData);

//            dispatch(function () use ($gamesData) {
//                resolve(TwitchGameService::class)->createMany($gamesData);
//            })->onQueue($defaultQueue);

            $time_end = microtime(true);
            $gl_time_exec += $time_end - $time_start;
            $this->alert($time_end - $time_start . " s");

            if (property_exists($currentResponse->getPagination(), 'cursor')) {
                $after = $currentResponse->getPagination()->cursor;
                $next = $after;
            } else {
                $next = false;
            }
        }

        $this->alert($gl_time_exec . " s");
    }
}
