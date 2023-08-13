<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LobbyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('lobby') || !session()->has('player')) {
            return redirect()->route('index');
        }

        return $next($request);
    }
}
