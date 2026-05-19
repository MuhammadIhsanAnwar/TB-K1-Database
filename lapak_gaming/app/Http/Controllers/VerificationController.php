<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\User; // Tambahkan ini untuk mengambil data user berdasarkan ID

class VerificationController extends Controller
{
    public function notice(Request $request): View|RedirectResponse
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->route('dashboard')
            : view('auth.verify-email');
    }

    public function resendGuest(Request $request)
{
    // Prioritize email provided by the POST form, fallback to session
    $email = $request->input('email') ?? session('guest_email');

    if (empty($email)) {
        return back()->with('error', 'Email tidak ditemukan.');
    }

    // cari user berdasarkan email
    $user = \App\Models\User::where('email', $email)->first();

    if (! $user) {
        return back()->with('error', 'User tidak ditemukan.');
    }

    try {
        // kirim ulang email verifikasi
        $user->sendEmailVerificationNotification();
        session(['guest_email' => $email]);

        return back()->with('success', 'Email verifikasi berhasil dikirim ulang.');
    } catch (\Throwable $exception) {
        return back()->with('error', 'Gagal mengirim email verifikasi. Silakan coba lagi nanti.');
    }
}

    public function send(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }

    public function verify(\Illuminate\Foundation\Auth\EmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill();

        return redirect()->route('dashboard');
    }

    public function pending(Request $request): View
    {
        return view('auth.verify-pending', [
            'email' => $request->query('email')
        ]);
    }

    /**
     * Menangani aktivasi akun dari link email publik (URL: /activate/{id}/{hash})
     */
    public function activate(Request $request, $id, $hash): RedirectResponse
    {
        // 1. Cari user berdasarkan ID yang ada di URL
        $user = User::findOrFail($id);

        // 2. Validasi hash email untuk keamanan (mencegah manipulasi url)
        if (!hash_equals(sha1($user->getEmailForVerification()), (string) $hash)) {
            abort(403, 'Link aktivasi tidak valid.');
        }

        // 3. Jika email belum diverifikasi, tandai sebagai verified
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            
            // Opsional: Picu event bawaan Laravel jika dibutuhkan package lain
            event(new \Illuminate\Auth\Events\Verified($user));
        }

        // 4. Otomatis login-kan user setelah berhasil aktivasi (Opsional, silakan hapus baris ini jika ingin user login manual)
        auth()->login($user);

        // 5. Alihkan ke halaman dashboard dengan pesan sukses
        return redirect()->route('dashboard')->with('verified', true);
    }
}