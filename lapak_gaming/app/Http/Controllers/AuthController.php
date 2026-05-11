<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Check if user exists and if email is verified
        $user = User::where('email', $credentials['email'])->first();
        
        if ($user && !$user->email_verified_at) {
            return back()->withErrors([
                'email' => 'Silakan verifikasi email Anda terlebih dahulu sebelum login.',
            ])->onlyInput('email');
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Email atau password tidak valid.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = $request->user();

        if ($user && $user->status === 'suspended') {
            Auth::logout();

            return back()->withErrors([
                'email' => 'Akun Anda sedang disuspend.',
            ]);
        }

        return redirect()->intended(match (true) {
            $user?->isSellerAccount() => route('seller.dashboard'),
            $user?->role === 'admin' => route('admin.dashboard'),
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
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal :min karakter.',
            'password.*' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan simbol.',
            'profile_photo.required' => 'Foto profil wajib diunggah.',
            'profile_photo.image' => 'File yang diunggah harus berupa gambar.',
            'profile_photo.max' => 'Ukuran foto maksimal 5MB.',
            'phone.required' => 'Nomor telepon wajib diisi.',
        ];

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:male,female,other'],
            'birth_date' => ['required', 'date', 'before:today'],
            'phone' => ['required', 'string', 'max:20'],
            'profile_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ], $messages);

        $user = DB::transaction(function () use ($request, $data) {
            $photo = $request->file('profile_photo');
            $avatarFilename = Str::uuid()->toString() . '.' . $photo->getClientOriginalExtension();
            Storage::disk('public_app_public')->putFileAs('user-avatars', $photo, $avatarFilename);
            $avatarPath = 'app/public/user-avatars/' . $avatarFilename;

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => 'buyer',
                'status' => 'active',
                'phone' => $data['phone'],
                'avatar' => $avatarPath,
            ]);

            $user->profile()->create([
                'gender' => $data['gender'],
                'birth_date' => $data['birth_date'],
                'phone' => $data['phone'],
                'avatar_path' => $avatarPath,
            ]);

            return $user;
        });

        $user->sendEmailVerificationNotification();

        return redirect()->route('login')->with('status', 'Registrasi berhasil! Silakan cek email Anda untuk verifikasi akun.');
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

        $googlePhone = $this->resolveGooglePhone($googleUser->token ?? null);
        $googleAvatarPath = $this->storeGoogleAvatar($googleUser->getAvatar(), (string) $googleUser->getId());
        $googleProfilePhone = $googlePhone ?: '-';
        $googleProfileBirthDate = '2000-01-01';

        // Find existing user by google_id or email
        $user = User::where('google_id', $googleUser->getId())->first()
            ?? User::where('email', $googleUser->getEmail())->first();

        if (! $user) {
            // Create new user without email verification
            $user = User::create([
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Pengguna Google',
                'email' => $googleUser->getEmail(),
                'password' => Str::random(40),
                'role' => 'buyer',
                'status' => 'active',
                'google_id' => $googleUser->getId(),
                'phone' => $googlePhone,
                'avatar' => $googleAvatarPath,
                // email_verified_at remains null - requires verification
            ]);
            
            $user->profile()->create([
                'gender' => 'other',
                // Provide safe defaults for legacy schemas where these columns are NOT NULL.
                'birth_date' => $googleProfileBirthDate,
                'phone' => $googleProfilePhone,
                'avatar_path' => $googleAvatarPath,
            ]);
        } else {
            // Link existing user with Google account
            $user->forceFill([
                'google_id' => $user->google_id ?: $googleUser->getId(),
                'phone' => $user->phone ?: $googlePhone,
                'avatar' => $user->avatar ?: $googleAvatarPath,
            ])->save();
        }

        if ($user->status === 'suspended') {
            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda sedang disuspend.',
            ]);
        }

        // Check if email is verified
        if (! $user->email_verified_at) {
            // Always send for any unverified Google user (new or existing).
            $user->sendEmailVerificationNotification();

            // Redirect to verification notice
            Auth::login($user);
            return redirect()->route('verification.notice');
        }

        // Email is verified, proceed with normal login
        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended(match ($user?->role) {
            'seller' => route('seller.dashboard'),
            'admin' => route('admin.dashboard'),
            default => route('buyer.dashboard'),
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
}
