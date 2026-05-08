@extends('layouts.app')

@section('title', 'Aktivasi Akun')

@section('content')
    <div class="mx-auto max-w-2xl rounded-4xl border border-slate-200 bg-white p-8 dark:border-slate-800 dark:bg-slate-900">
        <h1 class="text-3xl font-black">Aktivasi Akun</h1>

        @if(isset($already) && $already)
            <p class="mt-3 text-sm text-slate-500">Akun Anda telah aktif sebelumnya. Silakan masuk menggunakan akun Anda.</p>
        @else
            <p class="mt-3 text-sm text-slate-500">Terima kasih! Aktivasi berhasil. Sekarang akun Anda aktif dan dapat digunakan untuk masuk.</p>
        @endif

        <div class="mt-6">
            <a href="{{ route('login') }}" class="inline-block rounded-2xl bg-slate-950 px-4 py-3 font-bold text-white dark:bg-white dark:text-slate-950">Masuk ke Akun</a>
        </div>
    </div>
@endsection
