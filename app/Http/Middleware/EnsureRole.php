<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * @param  array<string>  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();

        if (! $user || ! in_array($user->role, $roles, true)) {
            // If not authorized, you can:
            // - abort(403)
            // - or redirect to login/home
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
