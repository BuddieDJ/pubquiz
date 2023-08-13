<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Lobby;
use App\Models\Player;
use Illuminate\Http\Request;

class LobbyController extends Controller
{
    public function index()
    {
        return view('lobby.index');
    }

    public function join(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'name' => 'required|string|min:3|max:255'
        ]);

        $lobby = Lobby::where('code', $request->code)->firstOrFail();

        $player = Player::create([
            'lobby_id' => $lobby->id,
            'name' => $request->name
        ]);

        session(['lobby' => $lobby]);
        session(['player' => $player]);

        return redirect()->route('lobby.show');
    }

    public function show()
    {
        return view('lobby.show');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'answer' => 'required'
        ]);

        $player = session('player');
        $lobby = session('lobby');

        $answer = Answer::create([
            'player_id' => $player->id,
            'lobby_id' => $lobby->id,
            'answer' => $request->answer
        ]);

        return response()->json($answer);
    }

    public function gamemaster()
    {
        $lobby = session('lobby');
        $players = $lobby->players()->with('answers')->get();

        return view('lobby.gamemaster', compact('players'));
    }

    public function answers()
    {
        $lobby = session('lobby');
        $players = $lobby->players;

        $response = [];
        foreach ($players as $player) {
            $response[] = [
                'name' => $player->name,
                'answer' => $player->latestAnswer->answer
            ];
        }

        $response = [
            'count' => count($response),
            'data' => $response
        ];

        return response()->json($response);
    }
}
