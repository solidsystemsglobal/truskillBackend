<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::middleware('guest')->group(function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
});

Route::get('twitch/games/top', 'Twitch\GameController@topGamesIcon');
Route::get('twitch/clips/trending', 'Twitch\ClipController@trendingClips');
Route::get('twitch/clips/popular', 'Twitch\ClipController@popularClips');
Route::get('twitch/clips/recent', 'Twitch\ClipController@recentClips');
Route::get('twitch/clips/single/{clip}', 'Twitch\ClipController@show');
Route::get('twitch/games/single/{clip}', 'Twitch\GameController@show');
//Route::get('twitch/clips/clip', 'Twitch\GameController@topGamesIcon');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', 'AuthController@logout');


    Route::prefix('twitch')->group(function () {

        Route::prefix('games')->group(function () {
            Route::get('', 'Twitch\GameController@index');
            Route::get('{game}', 'Twitch\GameController@show');
        });


        Route::prefix('clips')->group(function () {
            Route::get('', 'Twitch\ClipController@index');
            Route::get('{clip}', 'Twitch\ClipController@show');
        });
    });
});

