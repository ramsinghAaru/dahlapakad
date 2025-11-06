<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Player extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'room_id',
        'user_id',
        'name',
        'seat',
        'partner_seat',
        'device_id',
        'avatar',
        'is_owner',
        'is_ready',
        'joined_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_owner' => 'boolean',
        'is_ready' => 'boolean',
        'joined_at' => 'datetime',
    ];

    /**
     * Get the room that owns the player.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the user that owns the player.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
