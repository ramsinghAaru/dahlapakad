<?php
namespace App\Models; use Illuminate\Database\Eloquent\Model; class Room extends Model{ protected $fillable=['code','status','trump_method','settings']; protected $casts=['settings'=>'array']; public function players(){return $this->hasMany(Player::class);} public function games(){return $this->hasMany(Game::class);} }
