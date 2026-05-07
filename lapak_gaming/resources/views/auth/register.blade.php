@extends('layouts.app')

@section('title', 'Register')

@section('content')
<style>
    body.bg-grid {
        background-image: none !important;
    }
</style>
<div class="relative overflow-hidden bg-gradient-to-br from-blue-900 via-gray-900 to-orange-900 min-h-screen pt-16">

    {{-- Background Effect --}}
    <div class="absolute inset-0 opacity-20"
        style="background-image:
        radial-gradient(circle at 20% 20%, rgba(59,130,246,.35), transparent 25%),
        radial-gradient(circle at 80% 10%, rgba(249,115,22,.25), transparent 20%),
        radial-gradient(circle at 50% 80%, rgba(255,255,255,.08), transparent 24%);">
    </div>

    <div class="relative mx-auto grid max-w-7xl gap-8 px-4 lg:grid-cols-[0.9fr_1.1fr] lg:px-8">

        {{-- LEFT SIDE --}}
        <section class="rounded-[2rem] bg-white/10 p-8 text-white shadow-lg shadow-black/5 backdrop-blur-xl">

            <span class="inline-flex rounded-full border border-blue-400/30 bg-blue-400/10 px-4 py-1 text-xs font-semibold uppercase tracking-[0.3em] text-blue-200">
                Akun Baru
            </span>

            <h1 class="mt-6 text-4xl font-black leading-tight">
                Daftar dan mulai pengalaman gaming terbaikmu.
            </h1>

            <p class="mt-4 max-w-xl text-sm leading-6 text-gray-300">
                Lengkapi profil gamer kamu dengan mudah. Upload foto, isi data akun,
                dan mulai transaksi game dengan aman dan cepat.
            </p>

            <dl class="mt-8 grid gap-4 sm:grid-cols-2">

                <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                    <dt class="text-xs uppercase tracking-[0.25em] text-gray-400">
                        Upload Foto
                    </dt>

                    <dd class="mt-2 text-sm text-white">
                        Maksimal 5MB dan bisa dipotong langsung di browser.
                    </dd>
                </div>

                <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                    <dt class="text-xs uppercase tracking-[0.25em] text-gray-400">
                        Verifikasi Email
                    </dt>

                    <dd class="mt-2 text-sm text-white">
                        Akun aktif setelah link konfirmasi diklik.
                    </dd>
                </div>

            </dl>
        </section>

        {{-- RIGHT SIDE --}}
        <section class="rounded-[2rem] border border-white/10 bg-white/5 p-6 backdrop-blur-xl sm:p-8">

            <div class="flex items-center justify-between gap-4">

                <div>
                    <h2 class="text-3xl font-black text-white">
                        Register
                    </h2>

                    <p class="mt-2 text-sm text-gray-400">
                        Isi data akun, profil, dan alamat pengiriman.
                    </p>
                </div>

                <div class="hidden rounded-2xl border border-white/10 bg-black/20 px-4 py-2 text-xs font-semibold text-gray-300 md:block">
                    Lapak Gaming
                </div>

            </div>

            <form method="POST"
                action="{{ route('register') }}"
                enctype="multipart/form-data"
                class="mt-8 space-y-8"
                data-register-form>

                @csrf

                <div class="grid gap-6 sm:grid-cols-[160px_1fr] sm:items-start">

                    {{-- PHOTO --}}
                    <div>

                        <label class="mb-2 block text-sm font-semibold text-gray-300">
                            Foto Profil
                        </label>

                        <div class="space-y-3">

                            <img
                                src="https://ui-avatars.com/api/?name=Preview&background=111827&color=fff"
                                alt="Preview foto profil"
                                class="h-40 w-40 rounded-3xl border border-white/10 object-cover shadow-lg"
                                data-photo-preview
                            >

                            <input
                                name="profile_photo"
                                type="file"
                                accept="image/*"
                                class="block w-full text-sm text-gray-400
                                file:mr-4
                                file:rounded-2xl
                                file:border-0
                                file:bg-gradient-to-r
                                file:from-blue-500
                                file:to-orange-500
                                file:px-4
                                file:py-2
                                file:text-sm
                                file:font-semibold
                                file:text-white
                                hover:file:opacity-90"
                                data-photo-input
                            />

                            <p class="text-xs text-gray-500">
                                Format JPG, PNG, atau WEBP. Maksimal 5MB.
                            </p>

                            <p class="hidden text-sm font-medium text-red-400" data-photo-error></p>

                            @error('profile_photo')
                                <p class="text-sm text-red-400">{{ $message }}</p>
                            @enderror

                        </div>
                    </div>

                    {{-- FORM INPUT --}}
                    <div class="grid gap-4 sm:grid-cols-2">

                        @php
                            $inputClass = "w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 placeholder:text-gray-500";
                        @endphp

                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-gray-300">Username</span>
                            <input name="username" type="text" value="{{ old('username') }}" placeholder="username gamer" class="{{ $inputClass }}" />
                            @error('username') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-gray-300">Nama Lengkap</span>
                            <input name="name" type="text" value="{{ old('name') }}" placeholder="Nama lengkap" class="{{ $inputClass }}" />
                            @error('name') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-gray-300">Jenis Kelamin</span>
                            <select name="gender" class="{{ $inputClass }}">
                                <option value="">Pilih jenis kelamin</option>
                                <option value="male">Laki-laki</option>
                                <option value="female">Perempuan</option>
                                <option value="other">Lainnya</option>
                            </select>
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-gray-300">Tanggal Lahir</span>
                            <input name="birth_date" type="date" value="{{ old('birth_date') }}" class="{{ $inputClass }}" />
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-gray-300">Nomor Hp</span>
                            <input name="phone" type="tel" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" class="{{ $inputClass }}" />
                        </label>

                        <label class="block sm:col-span-2">
                            <span class="mb-2 block text-sm font-semibold text-gray-300">Email</span>
                            <input name="email" type="email" value="{{ old('email') }}" placeholder="nama@email.com" class="{{ $inputClass }}" />
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-gray-300">Password</span>
                            <input name="password" type="password" placeholder="Minimal 8 karakter" class="{{ $inputClass }}" />
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-gray-300">Konfirmasi Password</span>
                            <input name="password_confirmation" type="password" placeholder="Ulangi password" class="{{ $inputClass }}" />
                        </label>

                    </div>
                </div>

                {{-- ADDRESS --}}
                <div class="grid gap-4 sm:grid-cols-2">

                    <label class="block">
                        <span class="mb-2 block text-sm font-semibold text-gray-300">Provinsi</span>
                        <input name="province" type="text" value="{{ old('province') }}" class="{{ $inputClass }}" />
                    </label>

                    <label class="block">
                        <span class="mb-2 block text-sm font-semibold text-gray-300">Kabupaten/Kota</span>
                        <input name="regency" type="text" value="{{ old('regency') }}" class="{{ $inputClass }}" />
                    </label>

                    <label class="block">
                        <span class="mb-2 block text-sm font-semibold text-gray-300">Kecamatan</span>
                        <input name="district" type="text" value="{{ old('district') }}" class="{{ $inputClass }}" />
                    </label>

                    <label class="block">
                        <span class="mb-2 block text-sm font-semibold text-gray-300">Kelurahan/Desa</span>
                        <input name="village" type="text" value="{{ old('village') }}" class="{{ $inputClass }}" />
                    </label>

                    <label class="block sm:col-span-2 sm:max-w-xs">
                        <span class="mb-2 block text-sm font-semibold text-gray-300">Kode Pos</span>
                        <input name="postal_code" type="text" value="{{ old('postal_code') }}" class="{{ $inputClass }}" />
                    </label>

                    <label class="block sm:col-span-2">
                        <span class="mb-2 block text-sm font-semibold text-gray-300">Alamat Lengkap</span>

                        <textarea
                            name="full_address"
                            rows="4"
                            class="w-full rounded-3xl border border-white/10 bg-black/20 px-4 py-3 text-white outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 placeholder:text-gray-500">{{ old('full_address') }}</textarea>
                    </label>

                </div>

                {{-- BUTTON --}}
                <button class="inline-flex w-full items-center justify-center rounded-2xl bg-gradient-to-r from-blue-500 to-orange-500 px-4 py-3 font-bold text-white transition hover:scale-[1.01] hover:opacity-90 shadow-lg shadow-blue-500/10">
                    Buat Akun
                </button>

            </form>

            <div class="mt-5 text-sm text-gray-400">
                Sudah punya akun?
                <a href="{{ route('login') }}"
                    class="font-bold text-blue-400 hover:text-orange-400 transition">
                    Login
                </a>
            </div>

        </section>

    </div>
</div>
<style>
    footer {
        border-top: none !important;
        background: transparent !important;
    }
</style>
@endsection