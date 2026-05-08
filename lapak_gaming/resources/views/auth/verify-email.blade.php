@extends('layouts.app')

@section('title', 'Verifikasi Email — Lapak Gaming')

@push('styles')
<style>
    body.bg-grid {
        background-image: none !important;
    }
</style>
@endpush

@section('content')

<div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-slate-950 via-blue-950 to-slate-950 flex items-center justify-center py-16 px-4">

    {{-- Animated Background --}}
    <div class="absolute inset-0 opacity-40">
        <div class="absolute top-20 left-10 w-72 h-72 bg-green-600 rounded-full mix-blend-multiply filter blur-3xl animate-pulse"></div>
        <div class="absolute top-40 right-10 w-72 h-72 bg-blue-600 rounded-full mix-blend-multiply filter blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative w-full max-w-md">

        {{-- Card Container --}}
        <div class="rounded-3xl bg-gradient-to-br from-slate-900/50 to-blue-900/50 p-8 backdrop-blur-xl border border-white/10 shadow-2xl">

            {{-- Header with Icon --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-500/20 border border-green-500/30 mb-4">
                    <svg class="w-8 h-8 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 19v-8.93a6 6 0 01.89-2.79l7.78-9.44a6 6 0 018.66 0l7.78 9.44A6 6 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2m-7-14v6m0 0v6m0-6h6m-6 0H9" />
                    </svg>
                </div>

                <h1 class="text-3xl font-black text-white mb-2">Verifikasi Email</h1>
                <p class="text-slate-300">Kami telah mengirimkan link verifikasi ke email Anda</p>
            </div>

            {{-- Main Content --}}
            <div class="bg-white/5 rounded-2xl border border-white/10 p-6 mb-6">
                <p class="text-slate-300 text-sm leading-relaxed mb-4">
                    Silakan cek email Anda dan klik link verifikasi untuk mengaktifkan akun. Link akan berlaku selama 24 jam.
                </p>
                <p class="text-slate-400 text-xs">
                    <strong>Catatan:</strong> Periksa folder Spam jika email tidak ditemukan di Inbox.
                </p>
            </div>

            {{-- Resend Button --}}
            <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
                @csrf
                <button type="submit" class="w-full py-3.5 px-4 rounded-lg font-bold text-white text-base transition bg-gradient-to-r from-green-600 to-emerald-600 hover:shadow-lg hover:shadow-green-500/50 active:scale-95">
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            {{-- Back Link --}}
            <div class="text-center">
                <p class="text-slate-400 text-sm mb-2">Sudah verifikasi?</p>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1 text-blue-400 hover:text-orange-400 transition font-semibold text-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                    Lanjut ke Dashboard
                </a>
            </div>

            {{-- Info Box --}}
            <div class="mt-6 p-4 rounded-lg bg-blue-500/10 border border-blue-500/30">
                <p class="text-xs text-blue-300 leading-relaxed">
                    <strong>ℹ️ Informasi:</strong> Hanya user dengan email terverifikasi yang dapat mengakses semua fitur Lapak Gaming. Pastikan email Anda terverifikasi untuk melanjutkan.
                </p>
            </div>

        </div>

    </div>

</div>

@endsection