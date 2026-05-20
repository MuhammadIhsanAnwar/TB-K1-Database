<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'Akses ditolak.');
        }

        $allowed = false;
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                $allowed = true;
                break;
            }
        }

        if (! $allowed) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}