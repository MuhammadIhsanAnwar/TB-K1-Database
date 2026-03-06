<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use App\Models\EmailVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'required|in:buyer,seller',
            'phone' => 'nullable|string|max:20',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'is_active' => true,
        ]);

        // Create wallet
        Wallet::create([
            'user_id' => $user->id,
            'balance' => 0,
            'hold_balance' => 0,
        ]);

        // Create email verification token
        $token = Str::random(64);
        EmailVerification::create([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => now()->addHours(24),
        ]);

        // Send verification email
        $verificationUrl = route('email.verify', ['token' => $token]);
        // TODO: Send email via mail service

        return redirect()->route('email.verification.pending')
            ->with('success', 'Registrasi berhasil! Silakan verifikasi email Anda.');
    }

    public function verifyEmail($token)
    {
        $verification = EmailVerification::where('token', $token)->firstOrFail();

        if ($verification->isExpired()) {
            return redirect()->route('register')
                ->with('error', 'Token verifikasi sudah kadaluarsa.');
        }

        $user = $verification->user;
        $user->update(['email_verified_at' => now()]);
        $verification->delete();

        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Email berhasil diverifikasi!');
    }
}
