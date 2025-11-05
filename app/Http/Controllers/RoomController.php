<?php
namespace App\Http\Controllers;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoomController extends Controller{
  public function create(Request $r){
    $code = strtoupper(Str::random(6));
    $room = Room::create(['code'=>$code,'trump_method'=>$r->integer('trump_method',1)]);
    return response()->json(['code'=>$room->code]);
  }
  public function join(Request $r,$code){
    $room = Room::whereCode($code)->firstOrFail();
    if($room->players()->count()>=4) abort(409,'Room full');
    $seats=['N','E','S','W'];
    $taken=$room->players()->pluck('seat')->all();
    $seat=null; foreach($seats as $s){ if(!in_array($s,$taken)){ $seat=$s; break; } }
    $partner=['N'=>'S','E'=>'W','S'=>'N','W'=>'E'][$seat];
    $user=$r->user();
    $display=$r->string('name') ?: ($user->username ?? 'Player');
    $avatar=$r->string('avatar') ?: ($user->avatar ?? null);
    $room->players()->create([
      'user_id'=>$user?->id,
      'name'=>$display,
      'seat'=>$seat,
      'partner_seat'=>$partner,
      'device_id'=>$r->string('device_id'),
      'avatar'=>$avatar,
    ]);
    return response()->json(['seat'=>$seat,'partner'=>$partner]);
  }
  public function show($code){ return Room::with('players')->whereCode($code)->firstOrFail(); }
}
