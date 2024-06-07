<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ParticipationController;

Route::get('/', function () {
    return view('register');
});

Route::post('/register', [StudentController::class, 'register'])->name('register');
Route::get('/game', function () {
    return view('game');
});
Route::post('/submit', [ParticipationController::class, 'submit']);
Route::get('/leaderboard', [ParticipationController::class, 'leaderboard']);

