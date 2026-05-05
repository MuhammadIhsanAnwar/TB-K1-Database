@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
    <div class="mx-auto max-w-md rounded-4xl border border-slate-200 bg-white p-8 dark:border-slate-800 dark:bg-slate-900">
        <h1 class="text-3xl font-black">Reset Password</h1>
        <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-4">
            @csrf
            <input name="email" type="email" placeholder="Email" class="w-full rounded-2xl border border-slate-200 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" />
            <button class="w-full rounded-2xl bg-slate-950 px-4 py-3 font-bold text-white dark:bg-white dark:text-slate-950">Kirim Link Reset</button>
        </form>
    </div>
@endsection