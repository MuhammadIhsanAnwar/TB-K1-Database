@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <div class="mx-auto max-w-md rounded-[2rem] border border-slate-200 bg-white p-8 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <h1 class="text-3xl font-black">Register</h1>
        <p class="mt-2 text-sm text-slate-500">Buat akun buyer atau seller.</p>

        <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
            @csrf
            <input name="name" type="text" placeholder="Nama" class="w-full rounded-2xl border border-slate-200 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" />
            <input name="email" type="email" placeholder="Email" class="w-full rounded-2xl border border-slate-200 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" />
            <select name="role" class="w-full rounded-2xl border border-slate-200 px-4 py-3 dark:border-slate-700 dark:bg-slate-950">
                <option value="buyer">Buyer</option>
                <option value="seller">Seller</option>
            </select>
            <input name="password" type="password" placeholder="Password" class="w-full rounded-2xl border border-slate-200 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" />
            <input name="password_confirmation" type="password" placeholder="Konfirmasi password" class="w-full rounded-2xl border border-slate-200 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" />
            <button class="w-full rounded-2xl bg-slate-950 px-4 py-3 font-bold text-white dark:bg-white dark:text-slate-950">Register</button>
        </form>

        <div class="mt-4 text-sm text-slate-500">
            Sudah punya akun? <a href="{{ route('login') }}" class="font-bold text-slate-950 dark:text-white">Login</a>
        </div>
    </div>
@endsection