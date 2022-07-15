<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TwitchClip extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'twitch_clips';

//    protected $with = ['broadcaster'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'twitch_id',
        'game_id',
        'video_id',
        'url',
        'embed_url',
        'thumbnail_url',
        'broadcaster_id',
        'broadcaster_name',
        'creator_id',
        'creator_name',
        'language',
        'title',
        'view_count',
        'duration',
        'original_created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'original_created_at' => 'datetime',
    ];

    /**
     * The game of clip.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(TwitchGame::class, 'game_id', 'twitch_id');
    }

    public function broadcaster()
    {
        return $this->hasMany(TwitchClip::class, 'broadcaster_id', 'broadcaster_id');
    }
}
