<?php

namespace App\Http\Controllers\Traits;

use App\Models\TwitchGame;
use App\Exceptions\ApiException;

trait FindsTwitchGame
{
    /**
     * Get twitch game by id.
     *
     * @param  string|int $id
     * @return \App\Models\TwitchGame
     */
    protected function findTwitchGameById(string|int $id): TwitchGame
    {
        return $this->findTwitchGame(value: $id);
    }

    /**
     * Get twitch game by field and value.
     *
     * @param  string $field
     * @param  string $value
     * @return \App\Models\TwitchGame
     * @throws \App\Exceptions\ApiException
     */
    private function findTwitchGame(string $field = 'id', string $value): TwitchGame
    {
        $game = TwitchGame::where($field, $value)->first();

        if (!$game) {
            throw new ApiException(ApiException::NOT_FOUND, 'resources.twitch_game');
        }

        return $game;
    }
}
