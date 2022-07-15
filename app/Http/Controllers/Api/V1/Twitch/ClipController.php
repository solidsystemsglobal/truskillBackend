<?php

namespace App\Http\Controllers\Api\V1\Twitch;

use App\Models\TwitchClip;
use App\Http\Controllers\Api\Controller;
use App\Http\Controllers\Traits\FindsTwitchClip;
use App\Http\Resources\Api\V1\Twitch\Clip\TwitchClipResource;
use App\Services\Twitch\TwitchClipService;

class ClipController extends Controller
{
    use FindsTwitchClip;

    /**
     * @OA\Get(
     *      path="/api/twitch/clips",
     *      summary="Get the Twitch Clips",
     *      tags={"TwitchClips"},
     *      operationId="getTwitchClips",
     *      @OA\Parameter(
     *          ref="#/components/parameters/Authorization",
     *      ),
     *      @OA\Parameter(
     *          ref="#/components/parameters/repository-pagination-required",
     *      ),
     *      @OA\Parameter(
     *          ref="#/components/parameters/repository-sort",
     *      ),
     *      @OA\Parameter(
     *          ref="#/components/parameters/repository-filters",
     *      ),
     *      @OA\Parameter(
     *          ref="#/components/parameters/repository-search",
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Clips list returned",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="data",
     *                      type="array",
     *                      @OA\Items(
     *                          ref="#/components/schemas/TwitchClip",
     *                      ),
     *                  ),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Token expired or not provided",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  ref="#/components/schemas/ERR401",
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal error",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  ref="#/components/schemas/ERR500",
     *              ),
     *          ),
     *      ),
     * ),
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function index()
    {
        $clips = $this->fetchData(
            query: TwitchClip::query(),
            resourceClass: TwitchClipResource::class
        );

        return $this->response(TwitchClipResource::collection($clips));
    }

    /**
     * @OA\Get(
     *      path="/api/twitch/clips/{gameId}",
     *      summary="Get the twitch game details",
     *      tags={"TwitchClips"},
     *      operationId="getTwitchClipDetails",
     *      @OA\Parameter(
     *          ref="#/components/parameters/Authorization",
     *      ),
     *      @OA\Parameter(
     *          in="path",
     *          name="gameId",
     *          required=true,
     *          description="The game ID",
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Clip details returned",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="data",
     *                      ref="#/components/schemas/TwitchClip",
     *                  ),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Token expired or not provided",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  ref="#/components/schemas/ERR401",
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Clip not found",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  ref="#/components/schemas/ERR_NOT_FOUND",
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal error",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  ref="#/components/schemas/ERR500",
     *              ),
     *          ),
     *      ),
     * ),
     *
     * @param  mixed $gameId
     * @return \App\Http\Resources\Api\V1\Twitch\Clip\TwitchClipResource
     */
    public function show($gameId)
    {
        $game = $this->findTwitchClipById($gameId);

        return $this->response(TwitchClipResource::make($game));
    }

    public function trendingClips(TwitchClipService $twitchClipService)
    {
        $clips = $twitchClipService->trending();

        return $this->response(['trending_clips' => $clips]);
    }

    public function popularClips(TwitchClipService $twitchClipService)
    {
        $clips = $twitchClipService->popular();

        return $this->response(['popular_clips' => $clips]);
    }

    public function recentClips(TwitchClipService $twitchClipService)
    {
        $clips = $twitchClipService->recent();

        return $this->response(['recent_clips' => $clips]);
    }
}
