<?php

namespace App\Services\Twitch;

use App\Services\Service;
use App\Models\TwitchGame;
use App\Jobs\Twitch\Clips\GetTwitchClips;
use App\Jobs\Twitch\Games\FixTwitchGames;
use Illuminate\Database\Eloquent\Collection;

class TwitchGameService extends Service
{
    /**
     * Create a twitch game.
     *
     * @param  array $data
     * @param  bool $getClips
     * @return \App\Models\TwitchGame
     */
    public function create(array $data, bool $getClips = true): TwitchGame
    {
        try {
            $this->startTransaction();
            $game = TwitchGame::create($data);
            $this->commit();
        } catch (\Exception $exp) {
            $this->rollBack();

            throw $exp;
        }

        if ($getClips) {
            GetTwitchClips::dispatch($game);
        }

        return $game;
    }

    /**
     * Update the twitch game.
     *
     * @param  \App\Models\TwitchGame $game
     * @param  array $data
     * @return void
     */
    public function update(TwitchGame $game, array $data): void
    {
        try {
            $this->startTransaction();

            $game->update($data);

            $this->commit();
        } catch (\Exception $exp) {
            $this->rollBack();

            throw $exp;
        }

        // TicketModified::broadcast($logger);
    }

    /**
     * Delete the twitch game.
     *
     * @param  \App\Models\TwitchGame $game
     * @return void
     */
    public function delete(TwitchGame $game): void
    {
        try {
            $this->startTransaction();

            $game->delete();

            $this->commit();
        } catch (\Exception $exp) {
            $this->rollBack();

            throw $exp;
        }
    }

    /**
     * Create many games.
     *
     * @param  array $games
     * @param  bool $fix
     * @return void
     */
    public function createMany(array $games, bool $fix = false): void
    {
        $newGames = [];

        try {
            $this->startTransaction();

            foreach($games as $data) {
                $newGames[] = $this->create($data, false);
            }

            $this->commit();
        } catch (\Exception $exp) {
            $this->rollBack();

            throw $exp;
        }

        if ($fix) {
            $newGames = new Collection($newGames);
            FixTwitchGames::dispatch($newGames);
        }
    }

    public function topIcons()
    {
        try {
            $games = TwitchGame::query()->orderBy('updated_at', 'DESC')->limit(15)->get();
            return $games;
        } catch (\Exception $exp) {
            throw $exp;
        }
    }
}
