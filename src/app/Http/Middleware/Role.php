<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Role
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        // kein User → 401
        if (! $user) {
            abort(401);
        }

        // deaktiviert (role=null) → 403
        if (is_null($user->role)) {
            abort(403, 'Dein Account ist nicht freigeschaltet.');
        }

        // Admin darf alles
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Prüfen, ob Rolle passt
        if (! in_array($user->role, $roles, true)) {
            abort(403, 'Nicht erlaubt.');
        }

        return $next($request);
    }
}
