@extends('layouts.app')

@section('title', 'Daftar — Lapak Gaming')

@push('styles')
<style>
    body.bg-grid {
        background-image: none !important;
    }
    
    .gradient-border {
        position: relative;
        background: linear-gradient(135deg, #111827, #0f172a) border-box;
        border: 1px solid transparent;
        background-clip: padding-box;
    }
    
    .gradient-border::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        z-index: -1;
        margin: -1px;
        border-radius: inherit;
        padding: 1px;
        background: linear-gradient(135deg, rgba(37,99,235,0.3), rgba(249,115,22,0.2));
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
    }
</style>
@endpush

@section('content')

<div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-slate-950 via-blue-950 to-slate-950 py-16 px-4">

    {{-- Animated Background Elements --}}
    <div class="absolute inset-0 opacity-40">
        <div class="absolute top-20 left-10 w-72 h-72 bg-blue-600 rounded-full mix-blend-multiply filter blur-3xl animate-pulse"></div>
        <div class="absolute top-40 right-10 w-72 h-72 bg-orange-600 rounded-full mix-blend-multiply filter blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-1/2 w-72 h-72 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl animate-pulse" style="animation-delay: 4s;"></div>
    </div>



    <div class="relative mx-auto grid max-w-6xl gap-8 px-4 lg:grid-cols-2 lg:px-8">

        {{-- LEFT SIDE - Info Section --}}
        <section class="flex flex-col justify-between rounded-3xl bg-white/5 p-8 backdrop-blur-2xl border border-white/10 shadow-2xl">

            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-blue-400/30 bg-blue-400/10 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-blue-200 mb-8">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.5H7a1 1 0 100 2h4a1 1 0 001-1v-4.5z" clip-rule="evenodd"/>
                    </svg>
                    Akun Baru
                </div>

                <h1 class="text-5xl font-black leading-tight text-white mb-4">
                    Bergabunglah dengan Jutaan Gamer
                </h1>

                <p class="text-lg text-slate-300 leading-relaxed mb-8">
                    Rasakan pengalaman berbelanja game terbaik. Upload foto profil, isi data lengkap, dan mulai transaksi dengan aman dalam hitungan menit.
                </p>
            </div>

            <dl class="grid gap-4 mt-12">

                <div class="rounded-2xl border border-white/10 bg-gradient-to-br from-blue-500/10 to-orange-500/10 p-6 backdrop-blur">
                    <dt class="flex items-center gap-2 text-xs uppercase tracking-widest text-blue-300 font-bold mb-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                        </svg>
                        Upload Foto
                    </dt>
                    <dd class="text-sm text-slate-300">
                        JPG, PNG, atau WEBP hingga 5MB dengan crop langsung di browser.
                    </dd>
                </div>

                <div class="rounded-2xl border border-white/10 bg-gradient-to-br from-green-500/10 to-blue-500/10 p-6 backdrop-blur">
                    <dt class="flex items-center gap-2 text-xs uppercase tracking-widest text-green-300 font-bold mb-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                        Verifikasi Email
                    </dt>
                    <dd class="text-sm text-slate-300">
                        Klik link konfirmasi di email untuk aktivasi akun sepenuhnya.
                    </dd>
                </div>

                <div class="rounded-2xl border border-white/10 bg-gradient-to-br from-orange-500/10 to-red-500/10 p-6 backdrop-blur">
                    <dt class="flex items-center gap-2 text-xs uppercase tracking-widest text-orange-300 font-bold mb-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 2a1 1 0 011-1h8a1 1 0 011 1v1h1a1 1 0 011 1v1h1.22l-.5 1.5A3 3 0 0016.89 8H3.11a3 3 0 00-2.33 3.5L1 11v5a2 2 0 002 2h14a2 2 0 002-2v-5l.17-.5A3 3 0 0018.89 8h1.22l-.5-1.5H18V4a1 1 0 011-1h1V2a1 1 0 011-1h-8a1 1 0 011-1H5a1 1 0 011 1zm9 2H6v1h8V4z" clip-rule="evenodd"/>
                        </svg>
                        Siap Berbelanja
                    </dt>
                    <dd class="text-sm text-slate-300">
                        Akses semua fitur, mulai dari browse hingga checkout transaksi.
                    </dd>
                </div>

            </dl>
        </section>

        {{-- RIGHT SIDE - Form Section --}}
        <section class="rounded-3xl bg-gradient-to-br from-slate-900/50 to-blue-900/50 p-8 backdrop-blur-xl border border-white/10 shadow-2xl">

            <div class="mb-8">
                <h2 class="text-4xl font-black text-white mb-2">Daftar</h2>
                <p class="text-slate-400 text-sm">Isi form berikut untuk membuat akun baru</p>
            </div>

                    </div>

                    <div class="hidden rounded-2xl border border-white/10 bg-black/20 px-4 py-2 text-xs font-semibold text-gray-300 md:block">

                        Lapak Gaming

                    </div>



            </div>



            <form method="POST"
                action="{{ route('register') }}"
                enctype="multipart/form-data"
                class="mt-8 space-y-6"
                data-register-form>

                @csrf

                {{-- Photo Section --}}
                <div>
                    <label class="mb-3 block text-sm font-bold text-white">Foto Profil</label>
                    <div class="space-y-3">
                        <img
                            src="https://ui-avatars.com/api/?name=Preview&background=1e293b&color=fff&size=160"
                            alt="Preview foto profil"
                            class="h-36 w-36 rounded-2xl border border-white/10 object-cover shadow-lg"
                            data-photo-preview
                        >

                        <input
                            name="profile_photo"
                            type="file"
                            accept="image/*"
                            class="block w-full text-sm text-slate-400
                            file:mr-3
                            file:rounded-lg
                            file:border-0
                            file:bg-gradient-to-r
                            file:from-blue-500
                            file:to-orange-500
                            file:px-4
                            file:py-2
                            file:text-sm
                            file:font-semibold
                            file:text-white
                            hover:file:opacity-90
                            cursor-pointer"
                            data-photo-input
                        />

                        <p class="text-xs text-slate-500">
                            JPG, PNG, atau WEBP. Maksimal 5MB.
                        </p>

                        @error('profile_photo')
                            <p class="text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Form Fields --}}
                <div class="grid gap-4 sm:grid-cols-2">

                    @php
                        $inputClass = "w-full rounded-lg border border-slate-600/40 bg-slate-900/50 px-4 py-3 text-white outline-none transition focus:border-blue-500/60 focus:ring-2 focus:ring-blue-500/20 placeholder:text-slate-500";
                        $selectClass = $inputClass;
                    @endphp

                    {{-- Full Name --}}
                    <div class="sm:col-span-2">
                        <label for="name" class="mb-2 block text-sm font-bold text-white">Nama Lengkap</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" class="{{ $inputClass }}" required />
                        @error('name') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Gender --}}
                    <div>
                        <label for="gender" class="mb-2 block text-sm font-bold text-white">Jenis Kelamin</label>
                        <select id="gender" name="gender" class="{{ $selectClass }}" required>
                            <option value="">Pilih jenis kelamin</option>
                            <option value="male">Laki-laki</option>
                            <option value="female">Perempuan</option>
                            <option value="other">Lainnya</option>
                        </select>
                        @error('gender') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Birth Date --}}
                    <div>
                        <label for="birth_date" class="mb-2 block text-sm font-bold text-white">Tanggal Lahir</label>
                        <input id="birth_date" name="birth_date" type="date" value="{{ old('birth_date') }}" class="{{ $inputClass }}" required />
                        @error('birth_date') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label for="phone" class="mb-2 block text-sm font-bold text-white">Nomor Telepon</label>
                        <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" class="{{ $inputClass }}" required />
                        @error('phone') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div class="sm:col-span-2">
                        <label for="email" class="mb-2 block text-sm font-bold text-white">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="nama@email.com" class="{{ $inputClass }}" required />
                        @error('email') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Password --}}
                    <div class="sm:col-span-2">
                        <label for="password" class="mb-2 block text-sm font-bold text-white">Password</label>
                        <input id="password" name="password" type="password" placeholder="Minimal 8 karakter" class="{{ $inputClass }}" required />
                        <p class="mt-2 text-xs text-slate-400">Password harus minimal 8 karakter dan mengandung huruf besar, huruf kecil, angka, serta simbol.</p>
                        <ul class="mt-2 ml-4 text-xs text-slate-400 space-y-1" id="password-requirements">
                            <li data-rule="length">✓ Minimal 8 karakter</li>
                            <li data-rule="lower">✓ Huruf kecil (a-z)</li>
                            <li data-rule="upper">✓ Huruf besar (A-Z)</li>
                            <li data-rule="number">✓ Angka (0-9)</li>
                            <li data-rule="symbol">✓ Simbol (!@#$%^&*)</li>
                        </ul>
                    </div>

                    {{-- Confirm Password --}}
                    <div class="sm:col-span-2">
                        <label for="password_confirmation" class="mb-2 block text-sm font-bold text-white">Konfirmasi Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Ulangi password" class="{{ $inputClass }}" required />
                        <p class="hidden mt-1 text-sm text-red-400" id="password-match-error">Konfirmasi password tidak cocok.</p>
                    </div>

                </div>
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

                </div>

                {{-- Submit Buttons --}}
                <button type="submit" class="w-full py-3.5 px-4 rounded-lg font-bold text-white text-base transition bg-gradient-to-r from-blue-600 to-orange-600 hover:shadow-lg hover:shadow-blue-500/50 active:scale-95">
                    Buat Akun
                </button>

                <div class="flex items-center gap-3 my-4">
                    <div class="flex-1 h-px bg-slate-700"></div>
                    <span class="text-xs text-slate-500 font-medium">atau</span>
                    <div class="flex-1 h-px bg-slate-700"></div>
                </div>

                <a href="{{ route('google.auth') }}" class="inline-flex w-full items-center justify-center gap-3 rounded-lg border border-slate-600/40 bg-slate-900/50 px-4 py-3 font-semibold text-white transition hover:bg-slate-900/80 hover:border-slate-500/60">
                    <svg class="h-5 w-5" viewBox="0 0 48 48" aria-hidden="true">
                        <path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303C33.654 32.658 29.355 36 24 36c-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.957 3.043l5.657-5.657C34.041 6.053 29.272 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.651-.389-3.917z"/>
                        <path fill="#FF3D00" d="M6.306 14.691l6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.957 3.043l5.657-5.657C34.041 6.053 29.272 4 24 4c-7.682 0-14.358 4.337-17.694 10.691z"/>
                        <path fill="#4CAF50" d="M24 44c5.143 0 9.86-1.969 13.409-5.178l-6.191-5.238C29.173 35.091 26.763 36 24 36c-5.334 0-9.623-3.323-11.287-7.946l-6.522 5.025C9.48 39.556 16.227 44 24 44z"/>
                        <path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 0 1-4.085 5.584l.003-.002 6.191 5.238C36.96 39.101 44 34 44 24c0-1.341-.138-2.651-.389-3.917z"/>
                    </svg>
                    <span>Daftar dengan Google</span>
                </a>
            </form>

            {{-- Sign In Link --}}
            <p class="mt-6 text-center text-sm text-slate-400">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold text-blue-400 hover:text-orange-400 transition">Masuk di sini</a>
            </p>

            <script>
                (function () {
                    const form = document.querySelector('[data-register-form]');
                    if (!form) return;

                    const pwd = form.querySelector('input[name="password"]');
                    const pwdConfirm = form.querySelector('input[name="password_confirmation"]');

                    function checkPasswordRules(value) {
                        return {
                            length: value.length >= 8,
                            lower: /[a-z]/.test(value),
                            upper: /[A-Z]/.test(value),
                            number: /[0-9]/.test(value),
                            symbol: /[^A-Za-z0-9]/.test(value),
                        };
                    }

                    function updateRequirements() {
                        const list = document.getElementById('password-requirements');
                        if (!list || !pwd) return;
                        const rules = checkPasswordRules(pwd.value);
                        Array.from(list.querySelectorAll('li')).forEach(li => {
                            const rule = li.getAttribute('data-rule');
                            li.style.opacity = rules[rule] ? '0.6' : '1';
                            li.style.textDecoration = rules[rule] ? 'line-through' : 'none';
                        });
                    }

                    if (pwd) pwd.addEventListener('input', updateRequirements);

                    form.addEventListener('submit', function (e) {
                        if (!pwd) return;
                        const rules = checkPasswordRules(pwd.value);
                        const allOk = Object.values(rules).every(Boolean);

                        const matchError = document.getElementById('password-match-error');
                        if (pwdConfirm && pwd.value !== pwdConfirm.value) {
                            if (matchError) matchError.classList.remove('hidden');
                        } else {
                            if (matchError) matchError.classList.add('hidden');
                        }

                        if (!allOk || (pwdConfirm && pwd.value !== pwdConfirm.value)) {
                            e.preventDefault();
                            alert('Periksa kembali password Anda. Pastikan memenuhi semua syarat dan konfirmasi cocok.');
                        }
                    });
                })();
            </script>



        </section>



    </div>

</div>

<style>