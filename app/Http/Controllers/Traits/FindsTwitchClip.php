<?php

namespace App\Http\Controllers\Traits;

use App\Models\TwitchClip;
use App\Exceptions\ApiException;

trait FindsTwitchClip
{
    /**
     * Get twitch clip by id.
     *
     * @param  string|int $id
     * @return \App\Models\TwitchClip
     */
    protected function findTwitchClipById(string|int $id): TwitchClip
    {
        return $this->findTwitchClip(value: $id);
    }

    /**
     * Get twitch clip by field and value.
     *
     * @param  string $field
     * @param  string $value
     * @return \App\Models\TwitchClip
     * @throws \App\Exceptions\ApiException
     */
    private function findTwitchClip(string $field = 'id', string $value): TwitchClip
    {
        $clip = TwitchClip::query()->where($field, $value)->with('broadcaster')->first();
//        dd($clip);
        if (!$clip) {
            throw new ApiException(ApiException::NOT_FOUND, 'resources.twitch_clip');
        }

        return $clip;
    }
}
