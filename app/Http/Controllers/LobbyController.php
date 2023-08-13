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

        session()->put(['lobby' => $lobby]);
        session()->put(['player' => $player]);

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

        if ($answer = Answer::where('player_id', $player->id)->where('round', $lobby->round)->first()) {
            $answer->answer = $request->answer;
            $answer->save();
        } else {
            $answer = Answer::create([
                'player_id' => $player->id,
                'lobby_id' => $lobby->id,
                'answer' => $request->answer,
                'round' => $lobby->round
            ]);
        }

        return response()->json($answer);
    }

    public function gamemaster()
    {
        $lobby = session('lobby');
        $answers = Answer::where('lobby_id', $lobby->id)
            ->where('round', $lobby->round)->get();

        $response = [];
        foreach ($answers as $answer) {
            $response[] = [
                'id' => $answer->id,
                'name' => $answer->player->name,
                'answer' => $answer->answer,
                'is_correct' => $answer->is_correct ?? false
            ];
        }

        $answers = collect($response)->sortBy('name')->values()->all();

        return view('lobby.gamemaster', compact(['answers', 'lobby']));
    }

    public function answers()
    {
        $lobby = session('lobby');
        $answers = Answer::where('lobby_id', $lobby->id)
            ->where('round', $lobby->round)->get();

        $response = [];
        foreach ($answers as $answer) {
            $response[] = [
                'id' => $answer->id,
                'name' => $answer->player->name,
                'answer' => $answer->answer,
                'is_correct' => $answer->is_correct ?? false
            ];
        }

        $response = collect($response)->sortBy('name')->values()->all();

        return response()->json($response);
    }

    public function next()
    {
        $lobby = session('lobby');
        $lobby->increment('round');

        return redirect()->route('gamemaster');
    }

    public function create()
    {
        return view('lobby.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|min:3|max:255|unique:lobbies,code',
            'name' => 'required|string|min:3|max:255'
        ]);

        $lobby = Lobby::create([
            'code' => $request->code
        ]);

        $player = Player::create([
            'lobby_id' => $lobby->id,
            'name' => $request->name,
            'is_gamemaster' => true
        ]);

        session()->put(['lobby' => $lobby]);
        session()->put(['player' => $player]);

        return redirect()->route('gamemaster');
    }

    public function correct(Request $request)
    {
        $request->validate([
            'answer_id' => 'required|exists:answers,id'
        ]);

        $answer = Answer::findOrFail($request->answer_id);

        $answer->is_correct = !$answer->is_correct;
        $answer->save();

        return response()->json($answer);
    }

    public function analytics()
    {
        $lobby = session('lobby');
        $players = Player::where('lobby_id', $lobby->id)->get();

        $response = [];
        foreach ($players as $player) {
            $answers = Answer::where('lobby_id', $lobby->id)
                ->where('player_id', $player->id)
                ->where('is_correct', true)->count();

            $response[] = [
                'id' => $player->id,
                'name' => $player->name,
                'score' => $answers
            ];
        }

        $stats = collect($response)->sortByDesc('score')->values()->all();
        return response()->json($stats);
    }

    public function end()
    {
        $lobby = session('lobby');
        $players = Player::where('lobby_id', $lobby->id)->get();

        $response = [];
        foreach ($players as $player) {
            $answers = Answer::where('lobby_id', $lobby->id)
                ->where('player_id', $player->id)
                ->where('is_correct', true)->count();

            $response[] = [
                'id' => $player->id,
                'name' => $player->name,
                'score' => $answers
            ];
        }

        $stats = collect($response)->sortByDesc('score')->values()->all();
        return view('lobby.end', compact('stats'));
    }
}
