@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="mx-auto max-w-md rounded-[2rem] border border-slate-200 bg-white p-8 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <h1 class="text-3xl font-black">Login</h1>
        <p class="mt-2 text-sm text-slate-500">Masuk untuk buyer, seller, atau admin.</p>

        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
            @csrf
            <input name="email" type="email" placeholder="Email" class="w-full rounded-2xl border border-slate-200 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" />
            <input name="password" type="password" placeholder="Password" class="w-full rounded-2xl border border-slate-200 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" />
            <label class="flex items-center gap-2 text-sm text-slate-500"><input type="checkbox" name="remember"> Remember me</label>
            <button class="w-full rounded-2xl bg-slate-950 px-4 py-3 font-bold text-white dark:bg-white dark:text-slate-950">Login</button>
        </form>

        <div class="mt-4 flex items-center justify-between text-sm text-slate-500">
            <a href="{{ route('register') }}">Register</a>
            <a href="{{ route('password.request') }}">Forgot password?</a>
        </div>
    </div>
@endsection