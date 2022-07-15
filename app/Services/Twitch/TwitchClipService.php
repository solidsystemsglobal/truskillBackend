<?php

namespace App\Services\Twitch;

use App\Services\Service;
use App\Models\TwitchClip;
use App\Models\TwitchGame;
use App\Events\Twitch\ClipAdded;
use App\Events\Twitch\ClipDeleted;
use App\Events\Twitch\ClipModified;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class TwitchClipService extends Service
{
    /**
     * Create a twitch clip.
     *
     * @param  array $data
     * @return \App\Models\TwitchClip
     */
    public function create(array $data): TwitchClip
    {
        try {
            $this->startTransaction();

            $clip = TwitchClip::create($data);

            $this->commit();
        } catch (\Exception $exp) {
            $this->rollBack();

            throw $exp;
        }

        event(new ClipAdded($clip));

        return $clip;
    }

    /**
     * Update the twitch clip.
     *
     * @param  \App\Models\TwitchClip $clip
     * @param  array $data
     * @return void
     */
    public function update(TwitchClip $clip, array $data): void
    {
        try {
            $this->startTransaction();

            $clip->update($data);

            $this->commit();
        } catch (\Exception $exp) {
            $this->rollBack();

            throw $exp;
        }

        event(new ClipModified($clip));
    }

    /**
     * Delete the twitch clip.
     *
     * @param  \App\Models\TwitchClip $clip
     * @return void
     */
    public function delete(TwitchClip $clip): void
    {
        try {
            $this->startTransaction();

            $clip->delete();

            $this->commit();
        } catch (\Exception $exp) {
            $this->rollBack();

            throw $exp;
        }

        event(new ClipDeleted($clip));
    }

    /**
     * Create many clips related to one game.
     *
     * @param  \App\Models\TwitchGame $game
     * @param  array $clips
     * @return void
     */
    public function createMany(TwitchGame $game, array $clips): void
    {
        try {
            $newClips = [];
            $existsIds = $game->clips()->pluck('twitch_id')->toArray();

            foreach ($clips as $clip) {
                $clipData = [
                    'twitch_id' => $clip->id,
                    'url' => $clip->url,
                    'embed_url' => $clip->embed_url,
                    'broadcaster_id' => $clip->broadcaster_id,
                    'broadcaster_name' => $clip->broadcaster_name,
                    'creator_id' => $clip->creator_id,
                    'creator_name' => $clip->creator_name,
                    'video_id' => $clip->video_id,
                    'game_id' => $clip->game_id,
                    'language' => $clip->language,
                    'title' => $clip->title,
                    'view_count' => $clip->view_count,
                    'original_created_at' => $clip->created_at,
                    'thumbnail_url' => $clip->thumbnail_url,
                    'duration' => $clip->duration,
                ];

                if (in_array($clip->id, $existsIds)) {
                    $game->clips()->where('twitch_id', $clip->id)->update($clipData);
                } else {
                    $newClips[] = $clipData;
                }
            }

            if (!empty($newClips)) {
                $game->clips()->createMany($newClips);
            }
        } catch (\Exception $exp) {
            Log::error('Storing clips failed', [
                'exception' => $exp,
                'game' => $game->id
            ]);
        }
    }

    public function trending()
    {
        try {
            $clips = TwitchClip::query()
                ->orderBy('updated_at', 'DESC')
//                ->whereDate('updated_at', Carbon::now()
//                    ->subWeek())
                ->paginate(20);

            return $clips;
        } catch (\Exception $exp) {
            throw $exp;
        }
    }

    public function popular()
    {
        try {
            $clips = TwitchClip::query()
                ->orderBy('view_count', 'DESC')
//                ->whereDate('updated_at', Carbon::now()
//                    ->subMonth())
                ->paginate(20);
            return $clips;
        } catch (\Exception $exp) {
            throw $exp;
        }
    }

    public function recent()
    {
        try {
            $clips = TwitchClip::query()->orderBy('original_created_at', 'DESC')->paginate(20);
            return $clips;
        } catch (\Exception $exp) {
            throw $exp;
        }
    }
}
