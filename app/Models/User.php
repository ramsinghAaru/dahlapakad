<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'avatar',
        'rating',
        'games_played',
        'games_won',
        'highest_rating',
        'rank',
        'last_rank_update'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_rank_update' => 'datetime',
    ];

    protected $attributes = [
        'rating' => 1000,
        'games_played' => 0,
        'games_won' => 0,
        'highest_rating' => 1000,
        'rank' => 'Beginner',
    ];

    public function updateRanking($won = false)
    {
        $this->games_played++;
        
        if ($won) {
            $this->games_won++;
            $this->rating += 10;
            $this->highest_rating = max($this->highest_rating, $this->rating);
        } else {
            $this->rating = max(0, $this->rating - 5);
        }

        $this->updateRank();
        $this->save();
    }

    protected function updateRank()
    {
        $ranks = [
            2000 => 'Grand Master',
            1800 => 'Master',
            1600 => 'Expert',
            1400 => 'Advanced',
            1200 => 'Intermediate',
            1000 => 'Beginner',
            0 => 'Newbie'
        ];

        foreach ($ranks as $rating => $rank) {
            if ($this->rating >= $rating) {
                $this->rank = $rank;
                break;
            }
        }
        
        $this->last_rank_update = now();
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }
    
    /**
     * Get the user's active room (if any)
     */
    public function activeRoom()
    {
        return $this->hasOneThrough(
            Room::class,
            Player::class,
            'user_id', // Foreign key on players table
            'id', // Foreign key on rooms table
            'id', // Local key on users table
            'room_id' // Local key on players table
        )->whereIn('status', ['waiting', 'in_progress'])
         ->latest('players.created_at');
    }
}
