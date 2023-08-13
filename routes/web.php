<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LobbyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [LobbyController::class, 'index'])->name('index');
Route::post('/', [LobbyController::class, 'join'])->name('join');

Route::get('/aanmaken', [LobbyController::class, 'create'])->name('create');
Route::post('/aanmaken', [LobbyController::class, 'store'])->name('lobby.store');

Route::get('/analytics', [LobbyController::class, 'analytics'])->name('analytics');

Route::middleware(['lobby'])->group(function () {
    Route::get('/lobby', [LobbyController::class, 'show'])->name('lobby.show');
    Route::post('/lobby/submit', [LobbyController::class, 'submit'])->name('lobby.submit');

    Route::middleware(['gamemaster'])->group(function () {
        Route::get('/gamemaster', [LobbyController::class, 'gamemaster'])->name('gamemaster');
        Route::get('/gamemaster/answers', [LobbyController::class, 'answers'])->name('gamemaster.answers');
        Route::post('/gamemaster/next', [LobbyController::class, 'next'])->name('gamemaster.next');

        Route::post('/gamemaster/correct', [LobbyController::class, 'correct'])->name('gamemaster.correct');
        Route::post('/gamemaster/end', [LobbyController::class, 'end'])->name('gamemaster.end');
    });
});
