<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerificationController extends Controller
{
    public function notice(Request $request): View|RedirectResponse
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->route('dashboard')
            : view('auth.verify-email');
    }

    public function send(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        if (! $request->user()->sendEmailVerificationNotification()) {
            return back()->withErrors(['email' => 'Gagal mengirim email verifikasi. Silakan coba lagi nanti atau hubungi administrator.']);
        }

        return back()->with('status', 'verification-link-sent');
    }

    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill();

        return redirect()->route('dashboard');
    }

    // Public activation handler for signed activation links (works without auth)
    public function activate(Request $request, $id, $hash): RedirectResponse|\Illuminate\Contracts\View\View
    {
        $user = \App\Models\User::find($id);

        if (! $user) {
            return redirect()->route('login')->withErrors(['email' => 'Tautan aktivasi tidak valid.']);
        }

        if (sha1($user->getEmailForVerification()) !== $hash) {
            return redirect()->route('login')->withErrors(['email' => 'Tautan aktivasi tidak valid atau telah dimodifikasi.']);
        }

        if ($user->email_verified_at) {
            return view('auth.activation-success', ['already' => true, 'user' => $user]);
        }

        $user->email_verified_at = now();
        $user->save();

        return view('auth.activation-success', ['already' => false, 'user' => $user]);
    }
}