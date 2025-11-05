<?php
namespace App\Http\Controllers; use Illuminate\Http\Request; class AvatarController extends Controller{ public function upload(Request $r){ $r->validate(['avatar'=>'required|image|max:2048']); $path=$r->file('avatar')->store('avatars','public'); $user=$r->user(); $user->avatar='storage/'.$path; $user->save(); return response()->json(['avatar'=>$user->avatar]); }}
