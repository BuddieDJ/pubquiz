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

Route::middleware(['lobby'])->group(function () {
    Route::get('/lobby', [LobbyController::class, 'show'])->name('lobby.show');
    Route::post('/lobby/submit', [LobbyController::class, 'submit'])->name('lobby.submit');
    Route::get('/gamemaster', [LobbyController::class, 'gamemaster']);
    Route::get('/gamemaster/answers', [LobbyController::class, 'answers'])->name('gamemaster.answers');
});
