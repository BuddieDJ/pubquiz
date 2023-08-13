<?php

namespace App\Http\Middleware;

use App\Models\Lobby;
use App\Models\Player;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LobbyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('lobby') || !session()->has('player')) {
            $lobby = Lobby::find(session('lobby')->id);
            session(['lobby' => $lobby]);

            $player = Player::find(session('player')->id);
            session(['player' => $player]);

            return redirect()->route('index');
        }

        return $next($request);
    }
}
