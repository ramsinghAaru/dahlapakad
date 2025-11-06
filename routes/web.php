<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Authentication Routes
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Registration Routes...
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

// Public Routes
Route::view('/', 'home')->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/terms', 'terms')->name('terms');
Route::view('/privacy', 'privacy')->name('privacy');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Play route
    Route::get('/play', [App\Http\Controllers\RoomController::class, 'index'])->name('play');
    
    // Dashboard route
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
    
    // Profile routes
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    
    // Wallet routes
    Route::get('/wallet', [App\Http\Controllers\WalletController::class, 'index'])->name('wallet');
    
    // Rooms resource
    Route::resource('rooms', 'App\Http\Controllers\RoomController');
    
    // Custom room join route
    Route::post('/rooms/{room}/join', [App\Http\Controllers\RoomController::class, 'join'])
        ->name('rooms.join');
        
    // Room show route (for viewing a specific room)
    Route::get('/rooms/{room}', [App\Http\Controllers\RoomController::class, 'show'])
        ->name('rooms.show');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
