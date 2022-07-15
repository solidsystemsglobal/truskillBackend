<?php

namespace App\Http\Controllers\Api\V1\Twitch;

use App\Models\TwitchGame;
use App\Http\Controllers\Api\Controller;
use App\Http\Controllers\Traits\FindsTwitchGame;
use App\Http\Resources\Api\V1\Twitch\Game\TwitchGameResource;
use App\Services\Twitch\TwitchGameService;

class GameController extends Controller
{
    use FindsTwitchGame;

    /**
     * @OA\Get(
     *      path="/api/twitch/games",
     *      summary="Get the Twitch Games",
     *      tags={"TwitchGames"},
     *      operationId="getTwitchGames",
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
     *          description="Games list returned",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="data",
     *                      type="array",
     *                      @OA\Items(
     *                          ref="#/components/schemas/TwitchGame",
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
        $games = $this->fetchData(
            query: TwitchGame::query(),
            resourceClass: TwitchGameResource::class
        );

        return $this->response(TwitchGameResource::collection($games));
    }

    /**
     * @OA\Get(
     *      path="/api/twitch/games/{gameId}",
     *      summary="Get the twitch game details",
     *      tags={"TwitchGames"},
     *      operationId="getTwitchGameDetails",
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
     *          description="Game details returned",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="data",
     *                      ref="#/components/schemas/TwitchGame",
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
     *          description="Game not found",
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
     * @return \App\Http\Resources\Api\V1\Twitch\Game\TwitchGameResource
     */
    public function show($gameId)
    {
        $game = $this->findTwitchGameById($gameId);

        return $this->response(TwitchGameResource::make($game));
    }

    public function topGamesIcon(TwitchGameService $twitchGameService)
    {

        $games = $twitchGameService->topIcons();

        return $this->response(['icons' => $games]);
    }
}
