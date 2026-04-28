<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SetupController extends Controller
{
    /**
     * Tampilkan setup page
     */
    public function index()
    {
        // Jika sudah ada user admin, redirect ke dashboard
        if (User::where('role', 'admin')->exists()) {
            return redirect()->route('dashboard')->with('info', 'Admin sudah dibuat. Silakan login.');
        }

        return view('setup.admin-setup');
    }

    /**
     * Buat user admin pertama
     */
    public function storeAdmin(Request $request)
    {
        // Cek apakah setup sudah dilakukan
        if (User::where('role', 'admin')->exists()) {
            return redirect()->route('login')->with('info', 'Admin sudah dibuat sebelumnya.');
        }

        // Validasi form
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama admin harus diisi',
            'email.required' => 'Email harus diisi',
            'email.unique' => 'Email sudah terdaftar',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        try {
            // Buat user admin
            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'admin',
                'email_verified_at' => now(),
                'status' => 'active',
            ]);

            return redirect()->route('login')->with('success', '✅ Admin berhasil dibuat! Silakan login dengan email dan password yang Anda buat.');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Gagal membuat admin: ' . $e->getMessage());
        }
    }
}
