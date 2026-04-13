@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')
    <div class="mx-auto max-w-2xl rounded-[2rem] border border-slate-200 bg-white p-8 dark:border-slate-800 dark:bg-slate-900">
        <h1 class="text-3xl font-black">Verifikasi Email</h1>
        <p class="mt-3 text-sm text-slate-500">Cek inbox dan klik link verifikasi. Jika belum menerima, kirim ulang.</p>

        <form method="POST" action="{{ route('verification.send') }}" class="mt-6">
            @csrf
            <button class="rounded-2xl bg-slate-950 px-4 py-3 font-bold text-white dark:bg-white dark:text-slate-950">Kirim Ulang Verifikasi</button>
        </form>
    </div>
@endsection