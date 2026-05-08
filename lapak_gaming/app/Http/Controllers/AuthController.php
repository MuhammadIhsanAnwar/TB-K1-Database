<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
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

        return redirect()->intended(match ($user?->role) {
            'seller' => route('seller.dashboard'),
            'admin' => route('admin.dashboard'),
            default => route('buyer.dashboard'),
        });
    }

    public function createRegister(): View
    {
        return view('auth.register');
    }

    public function createRegisterSeller(): View
    {
        return view('auth.register-seller');
    }

    public function storeRegister(Request $request): RedirectResponse
    {
        $messages = [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan, coba yang lain.',
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
            'postal_code.regex' => 'Kode pos harus 5 digit angka.',
        ];

        $data = $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:male,female,other'],
            'birth_date' => ['required', 'date', 'before:today'],
            'phone' => ['required', 'string', 'max:20'],
            'profile_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'province' => ['required', 'string', 'max:100'],
            'regency' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'village' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'regex:/^\\d{5}$/'],
            'full_address' => ['required', 'string', 'max:1000'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ], $messages);

        $user = DB::transaction(function () use ($request, $data) {
            $user = User::create([
                'username' => $data['username'],
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => 'buyer',
                'status' => 'active',
            ]);

            $avatarPath = $request->file('profile_photo')->store('user-avatars', 'public');

            $user->profile()->create([
                'gender' => $data['gender'],
                'birth_date' => $data['birth_date'],
                'phone' => $data['phone'],
                'avatar_path' => $avatarPath,
            ]);

            $user->address()->create([
                'province' => $data['province'],
                'regency' => $data['regency'],
                'district' => $data['district'],
                'village' => $data['village'],
                'postal_code' => $data['postal_code'],
                'full_address' => $data['full_address'],
            ]);

            return $user;
        });

        event(new Registered($user));

        // Don't auto-login, force email verification first
        return redirect()->route('login')->with('status', 'Registrasi berhasil! Silakan cek email Anda untuk verifikasi akun.');
    }

    public function storeRegisterSeller(Request $request): RedirectResponse
    {
        $messages = [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan, coba yang lain.',
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
            'postal_code.regex' => 'Kode pos harus 5 digit angka.',
        ];

        $data = $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:male,female,other'],
            'birth_date' => ['required', 'date', 'before:today'],
            'phone' => ['required', 'string', 'max:20'],
            'bio' => ['nullable', 'string', 'max:500'],
            'profile_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'province' => ['required', 'string', 'max:100'],
            'regency' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'village' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'regex:/^\\d{5}$/'],
            'full_address' => ['required', 'string', 'max:1000'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ], $messages);

        $user = DB::transaction(function () use ($request, $data) {
            $user = User::create([
                'username' => $data['username'],
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => 'seller',
                'status' => 'active',
                'seller_level_id' => 1, // Starter level
            ]);

            $avatarPath = $request->file('profile_photo')->store('seller-logos', 'public');

            $user->profile()->create([
                'gender' => $data['gender'],
                'birth_date' => $data['birth_date'],
                'phone' => $data['phone'],
                'avatar_path' => $avatarPath,
                'bio' => $data['bio'] ?? null,
            ]);

            $user->address()->create([
                'province' => $data['province'],
                'regency' => $data['regency'],
                'district' => $data['district'],
                'village' => $data['village'],
                'postal_code' => $data['postal_code'],
                'full_address' => $data['full_address'],
            ]);

            return $user;
        });

        event(new Registered($user));

        // Don't auto-login, force email verification first
        return redirect()->route('login')->with('status', 'Registrasi berhasil! Silakan cek email Anda untuk verifikasi akun.');
    }

    public function google(Request $request): RedirectResponse
    {
        if ($request->has('code') || $request->has('error')) {
            return $this->handleGoogleCallback($request);
        }

        return redirect()->away(Socialite::driver('google')->redirect()->getTargetUrl());
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
        } catch (\Throwable $exception) {
            return redirect()->route('login')->withErrors([
                'email' => 'Gagal mengambil data akun Google. Silakan coba lagi.',
            ]);
        }

        $user = User::where('google_id', $googleUser->getId())->first();

        if (! $user) {
            $user = User::where('email', $googleUser->getEmail())->first();
        }

        if (! $user) {
            $user = User::create([
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Pengguna Google',
                'username' => $this->generateUniqueUsername($googleUser->getName() ?: $googleUser->getNickname() ?: $googleUser->getEmail()),
                'email' => $googleUser->getEmail(),
                'password' => Str::random(40),
                'role' => 'buyer',
                'status' => 'active',
                'google_id' => $googleUser->getId(),
            ]);
        } else {
            $user->forceFill([
                'google_id' => $user->google_id ?: $googleUser->getId(),
                'email_verified_at' => $user->email_verified_at ?: now(),
            ])->save();
        }

        if ($user->status === 'suspended') {
            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda sedang disuspend.',
            ]);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended(match ($user?->role) {
            'seller' => route('seller.dashboard'),
            'admin' => route('admin.dashboard'),
            default => route('buyer.dashboard'),
        });
    }

    protected function generateUniqueUsername(string $seed): string
    {
        $base = Str::slug(Str::before($seed, '@')) ?: 'google-user';
        $base = Str::limit($base, 40, '');

        $username = $base;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $suffix = '-' . $counter;
            $username = Str::limit($base, 50 - strlen($suffix), '') . $suffix;
            $counter++;
        }

        return $username;
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}