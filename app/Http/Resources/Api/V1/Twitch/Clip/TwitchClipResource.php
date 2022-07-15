<?php

namespace App\Http\Resources\Api\V1\Twitch\Clip;

use App\Repositories\V1\DataFieldMap;
use App\Http\Resources\Api\JsonResource;
use App\Http\Resources\Api\V1\Twitch\Game\TwitchGameResource;

/**
 * @OA\Schema(
 *    schema="TwitchClip",
 *    type="object",
 *    @OA\Property(
 *        property="id",
 *        type="integer",
 *        description="The clip ID",
 *        example=5,
 *    ),
 *    @OA\Property(
 *        property="twitchId",
 *        type="string",
 *        description="The clip twitch ID",
 *        example="CrypticSpineyToothTwitchRaid-HdLPQX7l2w-oaUOx",
 *    ),
 *    @OA\Property(
 *        property="game",
 *        ref="#/components/schemas/TwitchGame",
 *    ),
 *    @OA\Property(
 *        property="videoId",
 *        type="string",
 *        description="The video id",
 *        example="1446072581",
 *    ),
 *    @OA\Property(
 *        property="url",
 *        type="string",
 *        description="The url",
 *        example="https://clips.twitch.tv/CrypticSpineyToothTwitchRaid-HdLPQX7l2w-oaUOx",
 *    ),
 *    @OA\Property(
 *        property="embed",
 *        type="string",
 *        description="The embed url",
 *        example="https://clips.twitch.tv/embed?clip=CrypticSpineyToothTwitchRaid-HdLPQX7l2w-oaUOx",
 *    ),
 *    @OA\Property(
 *        property="thumbnail",
 *        type="string",
 *        description="The thumbnail url",
 *        example="https://clips-media-assets2.twitch.tv/AT-cm%7CmELjqR3Sz0F-5dsPqGlDsQ-preview-480x272.jpg",
 *    ),
 *    @OA\Property(
 *        property="broadcasterId",
 *        type="string",
 *        description="The broadcaster id",
 *        example="102381201",
 *    ),
 *    @OA\Property(
 *        property="broadcasterName",
 *        type="string",
 *        description="The broadcaster name",
 *        example="따효니",
 *    ),
 *    @OA\Property(
 *        property="creatorId",
 *        type="string",
 *        description="The creator id",
 *        example="225454689",
 *    ),
 *    @OA\Property(
 *        property="creatorName",
 *        type="string",
 *        description="The creator name",
 *        example="차부내",
 *    ),
 *    @OA\Property(
 *        property="language",
 *        type="string",
 *        description="The language",
 *        example="ko",
 *    ),
 *    @OA\Property(
 *        property="title",
 *        type="string",
 *        description="The title",
 *        example="ㅋㅋㅋㅋㅋㅋㅋ",
 *    ),
 *    @OA\Property(
 *        property="views",
 *        type="integer",
 *        description="The views count",
 *        example=6209,
 *    ),
 *    @OA\Property(
 *        property="duration",
 *        type="double",
 *        description="The duration",
 *        example=16.30,
 *    ),
 *    @OA\Property(
 *        property="createdAt",
 *        type="string",
 *        description="Creation datetime",
 *        example="2022-04-19 12:10:55",
 *    ),
 *    @OA\Property(
 *        property="importedAt",
 *        type="string",
 *        description="Importation datetime",
 *        example="2022-04-20 13:15:31",
 *    ),
 *    @OA\Property(
 *        property="updatedAt",
 *        type="string",
 *        description="Last modification datetime",
 *        example="2022-04-24 18:00:00",
 *    ),
 * ),
 */
class TwitchClipResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'twitchId' => $this->twitch_id,
            'game' => TwitchGameResource::make($this->game),
            'videoId' => $this->video_id,
            'url' => $this->url,
            'embed' => $this->embed_url,
            'thumbnail' => $this->thumbnail_url,
            'broadcasterId' => $this->broadcaster_id,
            'broadcasterName' => $this->broadcaster_name,
            'creatorId' => $this->creator_id,
            'creatorName' => $this->creator_name,
            'language' => $this->language,
            'title' => $this->title,
            'views' => $this->view_count,
            'duration' => $this->duration,
            'createdAt' => $this->toDateTime($this->original_created_at),
            'importedAt' => $this->toDateTime($this->created_at),
            'updatedAt' => $this->toDateTime($this->updated_at),
            'broadcaster' => $this->broadcaster,
        ];
    }

    /** {@inheritdoc} */
    public static function fieldMap(): ?DataFieldMap
    {
        $map = DataFieldMap::make();
        $map->set('views', 'view_count');
        $map->set('embed', 'embed_url');
        $map->set('thumbnail', 'thumbnail_url');
        $map->set('createdAt', 'original_created_at');
        $map->set('importedAt', 'created_at');
        $map->merge(TwitchGameResource::fieldMap(), 'game');
        $map->set('game.id', 'game_id');

        return $map;
    }

    /** {@inheritdoc} */
    public static function usedRelations(): array
    {
        return [
            'game' => TwitchGameResource::class,
        ];
    }
}
