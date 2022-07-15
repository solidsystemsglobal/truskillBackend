<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('twitch_clips', function (Blueprint $table) {
            $table->id();
            $table->string('twitch_id')->unique()->index();
            $table->string('game_id');
            $table->string('video_id');
            $table->string('url');
            $table->string('embed_url');
            $table->string('thumbnail_url');
            $table->string('broadcaster_id');
            $table->string('broadcaster_name');
            $table->string('creator_id');
            $table->string('creator_name');
            $table->string('language');
            $table->string('title');
            $table->unsignedBigInteger('view_count');
            $table->unsignedFloat('duration');
            $table->string('original_created_at');
            $table->timestamps();

            $table->foreign('game_id')
                ->references('twitch_id')
                ->on('twitch_games')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('twitch_clips');
    }
};
