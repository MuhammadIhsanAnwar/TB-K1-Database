<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SellerRegistrationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->isSellerAccount()) {
            return redirect()->route('seller.dashboard')->with('success', 'Akun Anda sudah terdaftar sebagai seller.');
        }

        $updates = [];

        if (Schema::hasColumn('users', 'is_seller')) {
            $updates['is_seller'] = true;
        }

        if (Schema::hasColumn('users', 'user_type')) {
            $updates['user_type'] = 'mixed';
        }

        if ($updates !== []) {
            $user->forceFill($updates)->save();
        }

        return redirect()->route('seller.dashboard')->with('success', 'Pendaftaran seller berhasil. Anda sekarang memiliki akses buyer & seller.');
    }
}
