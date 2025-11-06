<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, AvatarController, RoomController, GameController, UserController};

// Public routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/rooms/public', [RoomController::class, 'listPublicRooms']);
Route::get('/leaderboard', [UserController::class, 'leaderboard']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function() {
    // Authentication
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/avatar', [AvatarController::class, 'upload']);
    
    // User profile
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/profile', [UserController::class, 'updateProfile']);
    
    // Room management
    Route::post('/rooms', [RoomController::class, 'create']);
    Route::post('/rooms/{code}/join', [RoomController::class, 'join']);
    Route::get('/rooms/{code}', [RoomController::class, 'show']);
    Route::post('/rooms/{code}/leave', [RoomController::class, 'leaveRoom']);
    Route::post('/rooms/{code}/start', [RoomController::class, 'start']);
    Route::post('/rooms/{code}/end', [RoomController::class, 'endGame']);
    
    // Game actions
    Route::post('/games/{id}/play', [GameController::class, 'play']);
    Route::post('/games/{id}/select-trump', [GameController::class, 'select']);
    
    // Game history
    Route::get('/games', [GameController::class, 'history']);
    Route::get('/games/{id}', [GameController::class, 'show']);
});
