<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\TwoFactorChallengeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function createLogin(): View
    {
        return view('auth.login');
    }

    public function storeLogin(Request $request): RedirectResponse
    {
        $messages = [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.string' => 'Password harus berupa teks.',
        ];

        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ], $messages);

        // Check if user exists and if email is verified
        $user = User::where('email', $credentials['email'])->first();

        if ($user && ! $user->email_verified_at) {
            if (! Auth::validate($credentials)) {
                return back()->withErrors([
                    'email' => 'Email atau password tidak valid.',
                ])->onlyInput('email');
            }

            if (! $user->sendEmailVerificationNotification()) {
                return back()->withErrors([
                    'email' => 'Verifikasi email gagal dikirim. Silakan coba lagi nanti atau hubungi administrator.',
                ])->onlyInput('email');
            }

            return redirect()->route('verification.pending', ['email' => $user->email])
                ->with('status', 'Email verifikasi sudah dikirim ulang. Silakan cek kotak masuk Anda.');
        }

        if (! $user) {
            return back()->withErrors([
                'email' => 'Email atau password tidak valid.',
            ])->onlyInput('email');
        }

        if (! Auth::validate($credentials)) {
            return back()->withErrors([
                'email' => 'Email atau password tidak valid.',
            ])->onlyInput('email');
        }

        // ── Suspended account check (before 2FA/login finalization) ────────
        if ($user && $user->status === 'suspended') {
            $reason  = $user->suspend_reason;
            $message = 'Akun Anda telah disuspend oleh admin.';

            if ($reason) {
                $message .= ' Alasan: ' . $reason;
            }

            return back()->withErrors(['email' => $message])->onlyInput('email');
        }

        // ── Deactivated account check (before 2FA/login finalization) ─────
        if ($user && $user->deactivated_at) {
            if ($user->deactivated_at->copy()->addMonths(6)->isPast()) {
                $user->delete();

                return back()->withErrors([
                    'email' => 'Akun Anda telah dihapus permanen karena melewati batas waktu aktivasi.',
                ])->onlyInput('email');
            }

            $request->session()->put('reactivate_user_id', $user->id);

            return redirect()->route('account.reactivate.form');
        }

        if ($this->requiresTwoFactorChallenge($user)) {
            $twoFactor = app(TwoFactorChallengeService::class);
            $challengeMethod = $twoFactor->resolveLoginMethod($user);

            try {
                $twoFactor->sendLoginChallenge($user, $challengeMethod);
            } catch (\Throwable $exception) {
                return back()->withErrors([
                    'email' => 'Gagal mengirim kode verifikasi 2 langkah. Silakan coba lagi.',
                ])->onlyInput('email');
            }

            $request->session()->put('two_factor_login_pending', [
                'user_id' => $user->id,
                'remember' => $request->boolean('remember'),
                'method' => $challengeMethod,
            ]);

            return redirect()->route('two-factor.challenge')
                ->with('status', 'Masukkan kode verifikasi yang dikirim melalui ' . $twoFactor->methodLabel($challengeMethod) . ' untuk menyelesaikan login.');
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Email atau password tidak valid.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = $request->user();

        if ($user?->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(match (true) {
            $user?->isSellerAccount() => route('seller.dashboard'),
            default                   => route('buyer.dashboard'),
        });
    }

    public function twoFactorChallenge(): View|RedirectResponse
    {
        $pending = session('two_factor_login_pending');

        if (! is_array($pending) || empty($pending['user_id'])) {
            return redirect()->route('login');
        }

        $user = User::find($pending['user_id']);

        if (! $user) {
            session()->forget('two_factor_login_pending');

            return redirect()->route('login')->withErrors([
                'email' => 'Sesi verifikasi 2 langkah tidak valid. Silakan login kembali.',
            ]);
        }

        return view('auth.two-factor-challenge', [
            'user' => $user,
            'challengeMethod' => (string) ($pending['method'] ?? 'google'),
        ]);
    }

    public function confirmTwoFactorChallenge(Request $request): RedirectResponse
    {
        $pending = session('two_factor_login_pending');

        if (! is_array($pending) || empty($pending['user_id'])) {
            return redirect()->route('login')->withErrors([
                'email' => 'Sesi verifikasi 2 langkah tidak valid. Silakan login kembali.',
            ]);
        }

        $data = $request->validate([
            'verification_code' => ['required', 'digits:6'],
        ], [
            'verification_code.required' => 'Kode verifikasi wajib diisi.',
            'verification_code.digits' => 'Kode verifikasi harus terdiri dari 6 digit.',
        ]);

        $user = User::find($pending['user_id']);
        $challengeMethod = (string) ($pending['method'] ?? 'google');

        if (! $user || ! $this->requiresTwoFactorChallenge($user)) {
            session()->forget('two_factor_login_pending');

            return redirect()->route('login')->withErrors([
                'email' => 'Akun tidak lagi memerlukan verifikasi 2 langkah. Silakan login kembali.',
            ]);
        }

        $twoFactor = app(TwoFactorChallengeService::class);
        $isValid = $twoFactor->verifyLoginChallenge($user, $challengeMethod, $data['verification_code']);

        if (! $isValid) {
            return back()->withErrors([
                'verification_code' => 'Kode verifikasi tidak valid.',
            ])->withInput();
        }

        $twoFactor->clearLoginChallenge($user, $challengeMethod);

        Auth::login($user, (bool) ($pending['remember'] ?? false));
        session()->regenerate();
        session()->forget('two_factor_login_pending');

        return redirect()->intended(match (true) {
            $user->role === 'admin' => route('admin.dashboard'),
            $user->isSellerAccount() => route('seller.dashboard'),
            default => route('buyer.dashboard'),
        });
    }

    public function createRegister(): View
    {
        return view('auth.register');
    }

    public function storeRegister(Request $request): RedirectResponse
    {
        $messages = [
            'name.required'           => 'Nama lengkap wajib diisi.',
            'name.string'             => 'Nama harus berupa teks.',
            'name.max'                => 'Nama maksimal 255 karakter.',
            'gender.required'         => 'Jenis kelamin wajib dipilih.',
            'gender.in'               => 'Jenis kelamin tidak valid.',
            'birth_date.required'     => 'Tanggal lahir wajib diisi.',
            'birth_date.date'         => 'Format tanggal lahir tidak valid.',
            'birth_date.before'       => 'Tanggal lahir harus sebelum hari ini.',
            'phone.required'          => 'Nomor telepon wajib diisi.',
            'phone.string'            => 'Nomor telepon harus berupa angka.',
            'phone.max'               => 'Nomor telepon maksimal 20 karakter.',
            'profile_photo.required'  => 'Foto profil wajib diunggah.',
            'profile_photo.image'     => 'File yang diunggah harus berupa gambar.',
            'profile_photo.mimes'     => 'Format gambar harus JPG, JPEG, PNG, atau WebP.',
            'profile_photo.max'       => 'Ukuran foto maksimal 5MB.',
            'email.required'          => 'Email wajib diisi.',
            'email.email'             => 'Format email tidak valid.',
            'email.max'               => 'Email maksimal 255 karakter.',
            'email.unique'            => 'Email sudah terdaftar pada sistem.',
            'password.required'       => 'Password wajib diisi.',
            'password.confirmed'      => 'Konfirmasi password tidak cocok.',
            'password.min'            => 'Password minimal 8 karakter.',
            'password.*'              => 'Password harus mengandung huruf besar, huruf kecil, angka, dan simbol.',
        ];

        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'gender'        => ['required', 'in:male,female,other'],
            'birth_date'    => ['required', 'date', 'before:today'],
            'phone'         => ['required', 'string', 'max:20'],
            'profile_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'email'         => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'      => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ], $messages);

        $user = DB::transaction(function () use ($request, $data) {
            $photo          = $request->file('profile_photo');
            $avatarFilename = Str::uuid()->toString() . '.' . $photo->getClientOriginalExtension();
            Storage::disk('public_app_public')->putFileAs('user-avatars', $photo, $avatarFilename);
            $avatarPath = 'app/public/user-avatars/' . $avatarFilename;

            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => $data['password'],
                'role'     => 'buyer',
                'status'   => 'active',
                'phone'    => $data['phone'],
                'avatar'   => $avatarPath,
            ]);

            $user->profile()->create([
                'gender'     => $data['gender'],
                'birth_date' => $data['birth_date'],
                'phone'      => $data['phone'],
                'avatar_path' => $avatarPath,
            ]);

            return $user;
        });

        $sent = $user->sendEmailVerificationNotification();
        session(['guest_email' => $user->email]);

        if (! $sent) {
            return redirect()->route('verification.pending', ['email' => $user->email])
                ->with('warning', 'Registrasi berhasil, namun email verifikasi gagal dikirim. Silakan hubungi administrator atau periksa konfigurasi email.');
        }

        return redirect()->route('verification.pending', ['email' => $user->email])
            ->with('status', 'Registrasi berhasil! Silakan cek email Anda untuk verifikasi akun.');
    }

    public function google(Request $request): RedirectResponse
    {
        if ($request->has('code') || $request->has('error')) {
            return $this->handleGoogleCallback($request);
        }

        $googleProvider = Socialite::driver('google');

        if (method_exists($googleProvider, 'setScopes')) {
            $googleProvider->{'setScopes'}(['openid', 'profile', 'email']);
        }

        return redirect()->away($googleProvider->redirect()->getTargetUrl());
    }

    protected function handleGoogleCallback(Request $request): RedirectResponse
    {
        if ($request->filled('error')) {
            return redirect()->route('login')->withErrors([
                'email' => 'Login Google dibatalkan atau gagal diproses.',
            ]);
        }

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (InvalidStateException $exception) {
            try {
                $googleProvider = Socialite::driver('google');

                if (method_exists($googleProvider, 'stateless')) {
                    $googleProvider = $googleProvider->{'stateless'}();
                }

                $googleUser = $googleProvider->user();
            } catch (\Throwable $innerException) {
                return redirect()->route('login')->withErrors([
                    'email' => 'Login Google gagal karena sesi tidak cocok. Silakan coba lagi.',
                ]);
            }
        } catch (\Throwable $exception) {
            return redirect()->route('login')->withErrors([
                'email' => 'Gagal mengambil data akun Google. Silakan coba lagi.',
            ]);
        }

        $googlePhone      = $this->resolveGooglePhone($googleUser->token ?? null);
        $googleAvatarPath = $this->storeGoogleAvatar($googleUser->getAvatar(), (string) $googleUser->getId());

        $user = User::where('google_id', $googleUser->getId())->first()
            ?? User::where('email', $googleUser->getEmail())->first();

        if (! $user) {
            $user = User::create([
                'name'      => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Pengguna Google',
                'email'     => $googleUser->getEmail(),
                'password'  => Str::random(40),
                'role'      => 'buyer',
                'status'    => 'active',
                'google_id' => $googleUser->getId(),
                'phone'     => $googlePhone,
                'avatar'    => $googleAvatarPath,
            ]);

            $user->profile()->create([
                'gender'     => 'other',
                'birth_date' => '2000-01-01',
                'phone'      => $googlePhone ?: '-',
                'avatar_path' => $googleAvatarPath,
            ]);
        } else {
            $user->forceFill([
                'google_id' => $user->google_id ?: $googleUser->getId(),
                'phone'     => $user->phone ?: $googlePhone,
                'avatar'    => $user->avatar ?: $googleAvatarPath,
            ])->save();
        }

        if ($user->status === 'suspended') {
            $reason  = $user->suspend_reason;
            $message = 'Akun Anda telah disuspend oleh admin.';
            if ($reason) {
                $message .= ' Alasan: ' . $reason;
            }

            return redirect()->route('login')->withErrors(['email' => $message]);
        }

        if (! $user->email_verified_at) {
            $sent = $user->sendEmailVerificationNotification();

            if (! $sent) {
                return redirect()->route('login')->withErrors([
                    'email' => 'Verifikasi email gagal dikirim. Silakan hubungi administrator atau coba lagi nanti.',
                ]);
            }

            return redirect()->route('verification.pending', ['email' => $user->email])
                ->with('status', 'Email verifikasi sudah dikirim. Silakan cek kotak masuk Anda.');
        }

        if ($user->deactivated_at) {
            if ($user->deactivated_at->copy()->addMonths(6)->isPast()) {
                $user->delete();

                return redirect()->route('login')->withErrors([
                    'email' => 'Akun Anda telah dihapus permanen karena melewati batas waktu aktivasi.',
                ]);
            }

            $request->session()->regenerate();
            $request->session()->put('reactivate_user_id', $user->id);

            return redirect()->route('account.reactivate.form');
        }

        if ($this->requiresTwoFactorChallenge($user)) {
            $twoFactor = app(TwoFactorChallengeService::class);
            $challengeMethod = $twoFactor->resolveLoginMethod($user);

            try {
                $twoFactor->sendLoginChallenge($user, $challengeMethod);
            } catch (\Throwable $exception) {
                return redirect()->route('login')->withErrors([
                    'email' => 'Gagal mengirim kode verifikasi 2 langkah. Silakan coba lagi.',
                ]);
            }

            $request->session()->put('two_factor_login_pending', [
                'user_id' => $user->id,
                'remember' => true,
                'method' => $challengeMethod,
            ]);

            return redirect()->route('two-factor.challenge')
                ->with('status', 'Masukkan kode verifikasi yang dikirim melalui ' . $twoFactor->methodLabel($challengeMethod) . ' untuk menyelesaikan login.');
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended(match ($user?->role) {
            'seller' => route('seller.dashboard'),
            'admin'  => route('admin.dashboard'),
            default  => route('buyer.dashboard'),
        });
    }

    protected function resolveGooglePhone(?string $accessToken): ?string
    {
        if (! $accessToken) {
            return null;
        }

        try {
            $response = Http::withToken($accessToken)->get('https://people.googleapis.com/v1/people/me', [
                'personFields' => 'phoneNumbers',
            ]);

            if (! $response->successful()) {
                return null;
            }

            $phoneNumbers = data_get($response->json(), 'phoneNumbers', []);

            return collect($phoneNumbers)->pluck('value')->filter()->first();
        } catch (\Throwable $exception) {
            return null;
        }
    }

    protected function storeGoogleAvatar(?string $avatarUrl, string $googleId): ?string
    {
        if (! $avatarUrl) {
            return null;
        }

        try {
            $response = Http::timeout(15)->get($avatarUrl);

            if (! $response->successful()) {
                return null;
            }

            $avatarRelativePath = 'user-avatars/google-' . $googleId . '.jpg';
            Storage::disk('public_app_public')->put($avatarRelativePath, $response->body());

            return 'app/public/' . $avatarRelativePath;
        } catch (\Throwable $exception) {
            return null;
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function requiresTwoFactorChallenge(User $user): bool
    {
        return app(TwoFactorChallengeService::class)->requiresChallenge($user);
    }
}
