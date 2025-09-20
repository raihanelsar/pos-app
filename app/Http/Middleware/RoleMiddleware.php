<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $roles = array_map('intval', $roles);

        if (!in_array((int) $user->role_id, $roles)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
