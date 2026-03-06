<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if user exists and is verified
        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if (!$user || !$user->email_verified_at) {
            return back()->withErrors([
                'email' => 'Email belum diverifikasi atau tidak terdaftar.',
            ])->withInput($request->only('email'));
        }

        if ($user->is_suspended) {
            return back()->withErrors([
                'email' => 'Akun Anda telah dibekukan. Alasan: ' . $user->suspended_reason,
            ])->withInput($request->only('email'));
        }

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'password' => 'Password tidak sesuai.',
            ])->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        /** @var User|null $user */
        $user = Auth::user();
        if ($user && $user->isAdmin()) {
            return redirect()->route('home');
        }
        if ($user && $user->isSeller()) {
            return redirect()->route('seller.dashboard');
        }

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    protected function guard()
    {
        return auth();
    }
}
