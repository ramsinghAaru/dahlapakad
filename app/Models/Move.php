<?php
namespace App\Models; use Illuminate\Database\Eloquent\Model; class Move extends Model{ protected $fillable=['game_id','seat','card','trick_index']; public function game(){return $this->belongsTo(Game::class);} }
