<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HangmanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
    
//Route::post('/hangman/save', [HangmanController::class, 'saveGameState']);
Route::get('/hangman/load/{gameId}', [HangmanController::class, 'loadGameState']);});

Route::get('/hangman/word', [HangmanController::class, 'getWord']);
Route::post('/hangman/check', [HangmanController::class, 'checkLetter']);
Route::post('/hangman/register-player', [HangmanController::class, 'registerPlayer']);
Route::post('/hangman/create-match', [HangmanController::class, 'createMatch']);
Route::post('/hangman/record-move', [HangmanController::class, 'recordMove']);
