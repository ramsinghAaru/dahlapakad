<?php

use Illuminate\Support\Facades\Route;
Route::view('/', 'home');
Route::view('/about', 'about');
Route::view('/terms', 'terms');
Route::view('/privacy', 'privacy');
Route::view('/play', 'play');
