<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController,AvatarController,RoomController,GameController};

Route::post('/auth/register',[AuthController::class,'register']);
Route::post('/auth/login',[AuthController::class,'login']);
Route::middleware('auth:sanctum')->group(function(){
 Route::get('/auth/me',[AuthController::class,'me']);
 Route::post('/auth/logout',[AuthController::class,'logout']);
 Route::post('/auth/avatar',[AvatarController::class,'upload']);
 Route::post('/rooms',[RoomController::class,'create']);
 Route::post('/rooms/{code}/join',[RoomController::class,'join']);
 Route::get('/rooms/{code}',[RoomController::class,'show']);
 Route::post('/rooms/{code}/start',[GameController::class,'start']);
 Route::post('/games/{id}/play',[GameController::class,'play']);
 Route::post('/games/{id}/select-trump',[GameController::class,'select']);
});
