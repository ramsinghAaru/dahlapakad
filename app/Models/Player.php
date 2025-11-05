<?php
namespace App\Models; use Illuminate\Database\Eloquent\Model; class Player extends Model{ protected $fillable=['room_id','user_id','name','seat','partner_seat','device_id','avatar']; public function room(){return $this->belongsTo(Room::class);} public function user(){return $this->belongsTo(User::class);} }
