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
    public function startGame(Request $request, $matchId)
    {
        // Validate the request if needed
        $data = $request->validate([
            'player_id' => 'required|exists:players,id',
        ]);

        // Check if the match exists
        $match = Matche::where('match_id', $matchId)->first();
        if (!$match) {
            return response()->json(['error' => 'Match not found'], 404);
        }

        // Retrieve a random word for the game
        $word = HangmanWord::inRandomOrder()->first()->word;

        // Save the initial game state
        $initialGameState = [
            'currentWord' => $word,
            'correctGuesses' => [],
            'incorrectGuesses' => [],
        ];

        // Use the match_id as the cache key
        $cacheKey = 'hangman_game_' . $matchId;

        // Store the initial game state in cache
        Cache::put($cacheKey, $initialGameState, 60); // Store data in cache for 60 minutes

        return response()->json(['message' => 'Game started successfully', 'match_id' => $matchId]);
    }
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
    
    public function getPlayer($id)
    {
        // Assuming you want to retrieve a player based on the provided ID
        $player = Player::where('id', $id)->first();
    
        // Check if the player exists
        if ($player) {
            return response()->json(['name' => $player->name]);
        } else {
            // Handle the case when the player is not found
            return response()->json(['error' => 'Player not found'], 404);
        }
    }
    public function Players(){
        $players = Player::all();

        return response()->json(['players' => $players]);
    }
    



    public function createMatch(Request $request)
    {
        // Assuming you get match_name and player_id from the request or another source
        $matchName = $request->input('match_name');
        $playerId = $request->input('player_id');
    
        $match = Matche::create([
            'match_id' => Str::uuid(),
            'match_name' => $matchName,
            'player_id' => $playerId,
            // Add other fields as needed
        ]);
    
        return response()->json(['match_id' => $match->id]);
    }
    
    
    

}
