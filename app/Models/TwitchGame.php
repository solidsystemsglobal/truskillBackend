<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TwitchGame extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'twitch_games';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'twitch_id',
        'name',
        'box_art_url',
    ];

    /**
     * The game clips.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clips(): HasMany
    {
        return $this->hasMany(TwitchClip::class, 'game_id', 'twitch_id')->orderBy('view_count', 'desc');
    }

    /**
     * Always encrypt password when it is updated.
     *
     * @param $value
     * @return string
     */
    public function getBoxArtUrlAttribute($value)
    {
        return str_replace('{width}', 190, str_replace('{height}', 190, $value));
    }
}
