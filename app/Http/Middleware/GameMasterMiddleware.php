<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GameMasterMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('player') && session('player')->is_gamemaster) {
            return $next($request);
        }

        return redirect()->route('lobby.show');
    }
}
