<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('core.login');
        }

        return $next($request);
    }
}