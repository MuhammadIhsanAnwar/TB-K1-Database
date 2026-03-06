<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordResetController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot');
    }

    public function sendReset(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $validated['email'])->firstOrFail();

        // Create password reset token
        $token = Str::random(64);
        PasswordReset::create([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => now()->addHours(1),
        ]);

        // TODO: Send email with reset link
        $resetUrl = route('password.reset', ['token' => $token]);

        return back()->with('success', 'Link reset password telah dikirim ke email Anda.');
    }

    public function showResetForm($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->firstOrFail();

        if (!$passwordReset->isUsable()) {
            return redirect()->route('password.forgot')
                ->with('error', 'Token reset tidak valid atau sudah kadaluarsa.');
        }

        return view('auth.reset', ['token' => $token]);
    }

    public function reset(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $passwordReset = PasswordReset::where('token', $validated['token'])->firstOrFail();

        if (!$passwordReset->isUsable()) {
            return back()->with('error', 'Token reset tidak valid atau sudah kadaluarsa.');
        }

        $user = $passwordReset->user;
        $user->update(['password' => Hash::make($validated['password'])]);

        $passwordReset->update(['used' => true]);

        return redirect()->route('login')
            ->with('success', 'Password berhasil direset. Silakan login dengan password baru Anda.');
    }
}
