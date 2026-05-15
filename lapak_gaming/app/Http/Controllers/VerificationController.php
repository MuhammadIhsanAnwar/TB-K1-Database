<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerificationController extends Controller
{
    /**
     * Menampilkan halaman pemberitahuan verifikasi email untuk user yang login.
     */
    public function notice(Request $request): View|RedirectResponse
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->route('dashboard')
            : view('auth.verify-email');
    }

    /**
     * Mengirim ulang link verifikasi email untuk user yang login.
     */
    public function send(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }

    /**
     * Memproses verifikasi email setelah link diklik.
     */
    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill();

        return redirect()->route('dashboard');
    }

    /**
     * Menampilkan halaman tunggu verifikasi untuk tamu/setelah register Google (URL: /email/verify-pending).
     */
    public function pending(Request $request): View
    {
        // Mengembalikan view khusus verifikasi pending
        // Jika file view Anda bernama lain (misal: 'auth.verify-email'), silakan sesuaikan
        return view('auth.verify-pending', [
            'email' => $request->query('email')
        ]);
    }
}