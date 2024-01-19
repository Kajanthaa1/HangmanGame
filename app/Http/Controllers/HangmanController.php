<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Facade;
use App\Models\Matche;
use App\Models\Attempt;
use App\Models\HangmanWord;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


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

    public function getWord()
    {
        $word = HangmanWord::inRandomOrder()->first()->word;
        return response()->json(['word' => $word]);
    }

    public function checkLetter(Request $request)
    {
        $data = $request->validate([
            'letter' => 'required|string|max:1',
            'word' => 'required|string',
        ]);

        $guessedLetter = $data['letter'];
        $wordToGuess = $data['word'];

        $correctGuess = Str::contains($wordToGuess, $guessedLetter);

        return response()->json(['correct_guess' => $correctGuess]);
    }
    public function recordMove(Request $request)
    {
        $data = $request->validate([
            'match_id' => 'required|exists:matches,id',
            'player_id' => 'required|exists:players,id',
            'guessed_letter' => 'required|string|max:1',
            'time' => ''
        ]);
        $data['created_at'] = Carbon::now();
        Attempt::create($data);

        return response()->json(['message' => 'Move recorded successfully']);
    }
    public function registerPlayer(Request $request)
    
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        // Insert data into the players table using raw SQL query
        DB::table('players')->insert([
            'name' => $request->input('name'),
        ]);

        return response()->json(['message' => 'Player created successfully'], 201);
    }

    public function createMatch(Request $request)
    {
        $match = Matche::create(['match_id' => Str::uuid()]);

        return response()->json(['match_id' => $match->match_id]);
    }

}
