<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Auth::user()->email_verified_at) {
            return redirect()->route('email.verification.pending');
        }

        if (Auth::user()->is_suspended) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Akun Anda telah dibekukan.');
        }

        return $next($request);
    }
}
