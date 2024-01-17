<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HangmanController extends Controller
{
    public function saveGameState(Request $request)
    {
        $data = $request->validate([
            'currentWord' => 'required|string',
            'correctGuesses' => 'required|array',
            'incorrectGuesses' => 'required|array',
        ]);

        // Use a unique identifier for each user/game
        $gameId = md5(uniqid());

        Cache::put($gameId, $data, 60); // Store data in cache for 60 minutes

        return response()->json(['gameId' => $gameId]);
    }

    public function loadGameState($gameId)
    {
        $data = Cache::get($gameId);

        if (!$data) {
            return response()->json(['error' => 'Game not found'], 404);
        }

        return response()->json($data);
    }
}
Save to grepper
Update the api.php routes file to include the new routes:
php
Copy code
// routes/api.php

use App\Http\Controllers\HangmanController;

Route::post('/hangman/save', [HangmanController::class, 'saveGameState']);
Route::get('/hangman/load/{gameId}', [HangmanController::class, 'loadGameState']);
Save to grepper
Flutter Frontend:
Update the Flutter code to communicate with the Laravel API:

