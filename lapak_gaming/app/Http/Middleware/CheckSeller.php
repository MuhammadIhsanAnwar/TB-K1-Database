<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSeller
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User|null $user */
        $user = Auth::user();
        
        if (!$user || !$user->isSeller()) {
            return response()->view('errors.403', [], 403);
        }

        $sellerAccount = $user->sellerAccount;

        if (!$sellerAccount) {
            return redirect()->route('seller.setup');
        }

        if ($sellerAccount->is_banned) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Akun seller Anda telah dibekukan. Alasan: ' . $sellerAccount->ban_reason);
        }

        return $next($request);
    }
}
