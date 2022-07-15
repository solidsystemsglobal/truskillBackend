<?php

namespace App\Http\Resources\Api\V1\Twitch\Game;

use App\Http\Resources\Api\JsonResource;

/**
 * @OA\Schema(
 *    schema="TwitchGame",
 *    type="object",
 *    @OA\Property(
 *        property="id",
 *        type="integer",
 *        description="The game ID",
 *        example=5,
 *    ),
 *    @OA\Property(
 *        property="twitchId",
 *        type="string",
 *        description="The games' twitch ID",
 *        example="1000189302",
 *    ),
 *    @OA\Property(
 *        property="name",
 *        type="string",
 *        description="The games' name",
 *        example="That Role Playing",
 *    ),
 *    @OA\Property(
 *        property="image",
 *        type="string",
 *        description="The games' image url",
 *        example="https://static-cdn.jtvnw.net/ttv-boxart/1000189302-190x190.jpg",
 *    ),
 *    @OA\Property(
 *        property="createdAt",
 *        type="string",
 *        description="Creation datetime",
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
class TwitchGameResource extends JsonResource
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
            'name' => $this->name,
            'image' => $this->box_art_url,
            'createdAt' => $this->toDateTime($this->created_at),
            'updatedAt' => $this->toDateTime($this->updated_at),
        ];
    }
}
