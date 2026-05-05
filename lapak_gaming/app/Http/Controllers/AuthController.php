<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

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
        ]);

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

        Auth::login($user);

        return redirect()->route('verification.notice');
    }

    public function storeRegisterSeller(Request $request): RedirectResponse
    {
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
        ]);

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

        Auth::login($user);

        return redirect()->route('verification.notice');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}