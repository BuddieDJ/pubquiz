<?php

namespace App\Http\Middleware;

use App\Models\Player;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GameMasterMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('player') && session('player')->is_gamemaster) {
            $player = Player::find(session('player')->id);
            session(['player' => $player]);

            return $next($request);
        }

        return redirect()->route('lobby.show');
    }
}
