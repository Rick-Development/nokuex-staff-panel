<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastSeen
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('staff')->check()) {
            Auth::guard('staff')->user()->update([
                'last_seen' => now()
            ]);
        }

        return $next($request);
    }
}
