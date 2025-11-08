<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        if (Auth::guard('staff')->check()) {
            $staff = Auth::guard('staff')->user();
            if ($staff->hasPermission($permission) || $staff->hasPermission('*')) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized access.');
    }
}