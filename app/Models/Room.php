<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Room extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'status',
        'trump_method',
        'settings'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'settings' => 'array',
    ];

    /**
     * Get all players in the room.
     */
    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    /**
     * Get all users in the room.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'players', 'room_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * Get all games for the room.
     */
    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }
}
