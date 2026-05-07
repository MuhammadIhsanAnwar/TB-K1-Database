@extends('layouts.app')

@section('title', 'Register Seller')

@section('content')
    <div class="relative overflow-hidden bg-gradient-to-br from-blue-900 via-gray-900 to-orange-900 py-16 pb-20">
        <div class="absolute inset-0 opacity-20" aria-hidden="true"
             style="background-image: radial-gradient(circle at 20% 20%, rgba(217,119,6,.45), transparent 25%), radial-gradient(circle at 80% 10%, rgba(249,115,22,.35), transparent 20%), radial-gradient(circle at 50% 80%, rgba(217,119,6,.25), transparent 24%);">
        </div>

        <div class="relative mx-auto grid max-w-7xl gap-8 px-4 lg:grid-cols-[0.9fr_1.1fr] lg:px-8">
            <section class="rounded-[2rem] bg-white/5 backdrop-blur-xl p-6 shadow-2xl sm:p-8">
                <span class="inline-flex rounded-full border border-amber-400/30 bg-amber-400/10 px-4 py-1 text-xs font-semibold uppercase tracking-[0.3em] text-amber-200">Akun Penjual</span>
                <h1 class="mt-6 text-4xl font-black leading-tight">Mulai berjualan di Lapak Geming dengan modal minimal.</h1>
                <p class="mt-4 max-w-xl text-sm leading-6 text-slate-300">
                    Daftar sebagai penjual, lengkapi profil toko, dan mulai jual produk game favorit-mu. Sistem akan mengirim email konfirmasi sesuai konfigurasi mail.
                </p>

                <dl class="mt-8 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                        <dt class="text-xs uppercase tracking-[0.25em] text-slate-400">Foto Profil Toko</dt>
                        <dd class="mt-2 text-sm text-white">Maksimal 5MB, bisa dipotong langsung di browser.</dd>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                        <dt class="text-xs uppercase tracking-[0.25em] text-slate-400">Mulai Penjualan</dt>
                        <dd class="mt-2 text-sm text-white">Akun aktif setelah email verifikasi diklik.</dd>
                    </div>
                </dl>
            </section>

            <section class="rounded-[2rem] bg-black/20 p-6 backdrop-blur-xl shadow-none border border-white/5 sm:p-8">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-3xl font-black text-white"> Penjual</h2>
                        <p class="mt-2 text-sm text-gray-400">Isi data akun, profil toko, dan alamat bisnis.</p>
                    </div>
                    <div class="hidden rounded-2xl bg-amber-100 px-4 py-2 text-xs font-semibold text-amber-700 dark:bg-amber-900 dark:text-amber-200 md:block">
                        Seller
                    </div>
                </div>

                <form method="POST" action="{{ route('register.seller.store') }}" enctype="multipart/form-data" class="mt-8 space-y-8" data-register-form>
                    @csrf

                    <div class="grid gap-6 sm:grid-cols-[160px_1fr] sm:items-start">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Logo Toko</label>
                            <div class="space-y-3">
                                <img
                                    src="https://ui-avatars.com/api/?name=Store&background=f59e0b&color=fff"
                                    alt="Preview logo toko"
                                    class="h-40 w-40 rounded-3xl border border-slate-200 object-cover shadow-sm dark:border-slate-700"
                                    data-photo-preview
                                >
                                <input
                                    name="profile_photo"
                                    type="file"
                                    accept="image/*"
                                    class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-2xl file:border-0 file:bg-slate-950 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-800 dark:text-slate-400"
                                    data-photo-input
                                />
                                <p class="text-xs text-slate-500 dark:text-slate-400">Format JPG, PNG, atau WEBP. Maksimal 5MB.</p>
                                <p class="hidden text-sm font-medium text-rose-500" data-photo-error></p>
                                @error('profile_photo') <p class="text-sm text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <label class="block">
                                <span class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Username Toko</span>
                                <input name="username" type="text" value="{{ old('username') }}" placeholder="nama_toko" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-950 outline-none ring-0 transition focus:border-slate-950 dark:border-slate-700 dark:bg-slate-950 dark:text-white" />
                                @error('username') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                            </label>
                            <label class="block">
                                <span class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Nama Pemilik</span>
                                <input name="name" type="text" value="{{ old('name') }}" placeholder="Nama lengkap" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-950 outline-none ring-0 transition focus:border-slate-950 dark:border-slate-700 dark:bg-slate-950 dark:text-white" />
                                @error('name') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                            </label>
                            <label class="block">
                                <span class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Jenis Kelamin</span>
                                <select name="gender" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-950 outline-none transition focus:border-slate-950 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                                    <option value="">Pilih jenis kelamin</option>
                                    <option value="male" @selected(old('gender') === 'male')>Laki-laki</option>
                                    <option value="female" @selected(old('gender') === 'female')>Perempuan</option>
                                    <option value="other" @selected(old('gender') === 'other')>Lainnya</option>
                                </select>
                                @error('gender') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                            </label>
                            <label class="block">
                                <span class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Tanggal Lahir</span>
                                <input name="birth_date" type="date" value="{{ old('birth_date') }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-950 outline-none transition focus:border-slate-950 dark:border-slate-700 dark:bg-slate-950 dark:text-white" />
                                @error('birth_date') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                            </label>
                            <label class="block">
                                <span class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Nomor Hp</span>
                                <input name="phone" type="tel" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-950 outline-none transition focus:border-slate-950 dark:border-slate-700 dark:bg-slate-950 dark:text-white" />
                                @error('phone') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                            </label>
                            <label class="block">
                                <span class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Deskripsi Toko</span>
                                <textarea name="bio" rows="2" placeholder="Jelaskan tentang toko Anda" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-950 outline-none transition focus:border-slate-950 dark:border-slate-700 dark:bg-slate-950 dark:text-white">{{ old('bio') }}</textarea>
                                @error('bio') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                            </label>
                            <label class="block sm:col-span-2">
                                <span class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Email</span>
                                <input name="email" type="email" value="{{ old('email') }}" placeholder="nama@email.com" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-950 outline-none transition focus:border-slate-950 dark:border-slate-700 dark:bg-slate-950 dark:text-white" />
                                @error('email') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                            </label>
                            <label class="block">
                                <span class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Password</span>
                                <input name="password" type="password" placeholder="Minimal 8 karakter" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-950 outline-none transition focus:border-slate-950 dark:border-slate-700 dark:bg-slate-950 dark:text-white" />
                                @error('password') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                            </label>
                            <label class="block">
                                <span class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Konfirmasi Password</span>
                                <input name="password_confirmation" type="password" placeholder="Ulangi password" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-950 outline-none transition focus:border-slate-950 dark:border-slate-700 dark:bg-slate-950 dark:text-white" />
                            </label>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Provinsi</span>
                            <input name="province" type="text" value="{{ old('province') }}" placeholder="Provinsi" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-950 outline-none transition focus:border-slate-950 dark:border-slate-700 dark:bg-slate-950 dark:text-white" />
                            @error('province') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                        </label>
                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Kabupaten/Kota</span>
                            <input name="regency" type="text" value="{{ old('regency') }}" placeholder="Kabupaten / Kota" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-950 outline-none transition focus:border-slate-950 dark:border-slate-700 dark:bg-slate-950 dark:text-white" />
                            @error('regency') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                        </label>
                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Kecamatan</span>
                            <input name="district" type="text" value="{{ old('district') }}" placeholder="Kecamatan" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-950 outline-none transition focus:border-slate-950 dark:border-slate-700 dark:bg-slate-950 dark:text-white" />
                            @error('district') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                        </label>
                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Kelurahan/Desa</span>
                            <input name="village" type="text" value="{{ old('village') }}" placeholder="Kelurahan / Desa" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-950 outline-none transition focus:border-slate-950 dark:border-slate-700 dark:bg-slate-950 dark:text-white" />
                            @error('village') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                        </label>
                        <label class="block sm:col-span-2 sm:max-w-xs">
                            <span class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Kode Pos</span>
                            <input name="postal_code" type="text" value="{{ old('postal_code') }}" placeholder="12345" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-950 outline-none transition focus:border-slate-950 dark:border-slate-700 dark:bg-slate-950 dark:text-white" />
                            @error('postal_code') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                        </label>
                        <label class="block sm:col-span-2">
                            <span class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-300">Alamat Bisnis</span>
                            <textarea name="full_address" rows="4" placeholder="Alamat lengkap toko" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-slate-950 outline-none transition focus:border-slate-950 dark:border-slate-700 dark:bg-slate-950 dark:text-white">{{ old('full_address') }}</textarea>
                            @error('full_address') <p class="mt-2 text-sm text-rose-500">{{ $message }}</p> @enderror
                        </label>
                    </div>

                    <button class="inline-flex w-full items-center justify-center rounded-2xl bg-amber-600 px-4 py-3 font-bold text-white transition hover:bg-amber-700 dark:bg-amber-500 dark:hover:bg-amber-600">
                        Buat Toko
                    </button>
                </form>

                <div class="mt-5 text-sm text-slate-500 dark:text-slate-400">
                    Ingin membeli saja? <a href="{{ route('register') }}" class="font-bold text-slate-950 underline-offset-4 hover:underline dark:text-white">Daftar sebagai pembeli</a>
                </div>
            </section>
        </div>

        <div class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/80 px-4 py-8 backdrop-blur-sm" data-crop-modal>
            <div class="w-full max-w-3xl rounded-[2rem] border border-white/10 bg-slate-900 p-4 text-white shadow-2xl shadow-black/40">
                <div class="flex items-center justify-between gap-4 px-2 py-3">
                    <div>
                        <h3 class="text-xl font-bold">Crop logo toko</h3>
                        <p class="text-sm text-slate-400">Pilih area terbaik sebelum menyimpan.</p>
                    </div>
                    <button type="button" class="rounded-full bg-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/15" data-crop-cancel>Batalkan</button>
                </div>

                <div class="overflow-hidden rounded-[1.5rem] bg-black">
                    <img src="" alt="Crop preview" class="block max-h-[70vh] w-full object-contain" data-crop-image>
                </div>

                <div class="flex flex-wrap items-center justify-end gap-3 px-2 py-4">
                    <button type="button" class="rounded-2xl border border-white/15 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10" data-crop-reset>Reset</button>
                    <button type="button" class="rounded-2xl bg-amber-400 px-4 py-2 text-sm font-semibold text-slate-950 hover:bg-amber-300" data-crop-save>Simpan Crop</button>
                </div>
            </div>
        </div>
    </div>
    <style>
    footer {
        border-top: none !important;
        background: transparent !important;
    }

    body.bg-grid {
        background-image: none !important;
    }
</style>
@endsection
