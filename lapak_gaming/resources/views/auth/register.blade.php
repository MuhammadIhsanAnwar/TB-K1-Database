@extends('layouts.app')



@section('title', 'Register')



@section('content')

<style>

    body.bg-grid {

        background-image: none !important;

    }

</style>

<div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-blue-900 via-gray-900 to-orange-900 py-16">



    {{-- Background Effect --}}

    <div class="absolute inset-0 opacity-20"

        style="background-image:

        radial-gradient(circle at 20% 20%, rgba(59,130,246,.35), transparent 25%),

        radial-gradient(circle at 80% 10%, rgba(249,115,22,.25), transparent 20%),

        radial-gradient(circle at 50% 80%, rgba(255,255,255,.08), transparent 24%);">

    </div>



    <div class="relative mx-auto grid max-w-7xl gap-8 px-4 lg:grid-cols-[0.9fr_1.1fr] lg:px-8">



        {{-- LEFT SIDE --}}

        <section class="rounded-[2rem] bg-white/5 p-8 backdrop-blur-xl shadow-none border-0">



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

        <section class="rounded-[2rem] bg-black/20 p-6 backdrop-blur-xl shadow-none border border-white/5 sm:p-8">



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

                            <input name="password" id="password" type="password" placeholder="Minimal 8 karakter" class="{{ $inputClass }}" />

                            <p class="mt-2 text-sm text-gray-300" id="password-hint">Password harus minimal 8 karakter dan mengandung huruf besar, huruf kecil, angka, serta simbol.</p>

                            <ul class="mt-2 ml-4 text-sm text-gray-300" id="password-requirements">
                                <li data-rule="length">Minimal 8 karakter</li>
                                <li data-rule="lower">Mengandung huruf kecil (a-z)</li>
                                <li data-rule="upper">Mengandung huruf besar (A-Z)</li>
                                <li data-rule="number">Mengandung angka (0-9)</li>
                                <li data-rule="symbol">Mengandung simbol (contoh: !@#)</li>
                            </ul>

                        </label>



                        <label class="block">

                            <span class="mb-2 block text-sm font-semibold text-gray-300">Konfirmasi Password</span>

                            <input name="password_confirmation" id="password_confirmation" type="password" placeholder="Ulangi password" class="{{ $inputClass }}" />

                            <p class="hidden mt-2 text-sm text-red-400" id="password-match-error">Konfirmasi password tidak cocok.</p>

                        </label>



                    </div>

                </div>
                {{-- BUTTON --}}

                <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-gradient-to-r from-blue-500 to-orange-500 px-4 py-3 font-bold text-white transition hover:scale-[1.01] hover:opacity-90 shadow-lg shadow-blue-500/10">

                    Buat Akun

                </button>

                <a href="{{ route('google.auth') }}" class="inline-flex w-full items-center justify-center gap-3 rounded-2xl border border-white/10 bg-black/30 px-4 py-3 font-semibold text-white transition hover:bg-black/40">
                    <svg class="h-5 w-5" viewBox="0 0 48 48" aria-hidden="true">
                        <path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303C33.654 32.658 29.355 36 24 36c-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.957 3.043l5.657-5.657C34.041 6.053 29.272 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.651-.389-3.917z"/>
                        <path fill="#FF3D00" d="M6.306 14.691l6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.957 3.043l5.657-5.657C34.041 6.053 29.272 4 24 4c-7.682 0-14.358 4.337-17.694 10.691z"/>
                        <path fill="#4CAF50" d="M24 44c5.143 0 9.86-1.969 13.409-5.178l-6.191-5.238C29.173 35.091 26.763 36 24 36c-5.334 0-9.623-3.323-11.287-7.946l-6.522 5.025C9.48 39.556 16.227 44 24 44z"/>
                        <path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 0 1-4.085 5.584l.003-.002 6.191 5.238C36.96 39.101 44 34 44 24c0-1.341-.138-2.651-.389-3.917z"/>
                    </svg>
                    Daftar dengan Google
                </a>



            </form>



            <div class="mt-5 text-sm text-gray-400">

                Sudah punya akun?

                <a href="{{ route('login') }}" class="font-bold text-blue-400 hover:text-orange-400 transition">Login</a>

            </div>


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