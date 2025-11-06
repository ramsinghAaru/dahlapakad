<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PlayerPivot extends Pivot
{
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
     * Get the player's name.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->pivotParent->name ?? null;
    }
}
