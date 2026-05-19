@extends('layouts.app')
@section('title', 'Verifikasi 2 Langkah — Lapak Gaming')

@push('styles')
<style>
  .challenge-bg {
    background: radial-gradient(ellipse 70% 55% at 50% -5%, rgba(37,99,235,0.22) 0%, rgba(249,115,22,0.06) 60%, transparent 100%);
  }
  .challenge-card {
    position: relative;
    background: linear-gradient(145deg, #0D1421 0%, #0A1120 100%);
    border-radius: 20px;
    overflow: hidden;
  }
  .challenge-card::before {
    content: '';
    position: absolute; inset: 0;
    border-radius: 20px;
    padding: 1px;
    background: linear-gradient(135deg, rgba(37,99,235,0.55) 0%, rgba(37,99,235,0.1) 40%, rgba(249,115,22,0.35) 100%);
    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor; mask-composite: exclude;
    pointer-events: none;
  }
  .challenge-input {
    width: 100%;
    padding: 0.95rem 1rem;
    border-radius: 12px;
    border: 1px solid #1E2D45;
    background: #090E1A;
    color: #fff;
    outline: none;
  }
  .challenge-input:focus {
    border-color: rgba(37,99,235,0.6);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
  }
  .challenge-btn {
    width: 100%;
    padding: 0.95rem 1rem;
    border-radius: 12px;
    border: 1px solid rgba(96,165,250,0.35);
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 50%, #1e40af 100%);
    color: #fff;
    font-weight: 700;
  }
</style>
@endpush

@section('content')
@php
  $challengeMethod = $challengeMethod ?? 'google';
  $challengeTitle = match ($challengeMethod) {
    'sms' => 'SMS',
    'email' => 'Email',
    default => 'Google Authenticator',
  };
  $challengeHelp = match ($challengeMethod) {
    'sms' => 'Masukkan kode yang dikirim ke nomor telepon akun Anda.',
    'email' => 'Masukkan kode yang dikirim ke email akun Anda.',
    default => 'Masukkan kode dari Google Authenticator untuk melanjutkan login ke akun ' . $user->email . '.',
  };
@endphp

<div class="relative min-h-screen flex items-center justify-center py-16 px-4 overflow-hidden">
  <div class="challenge-bg absolute inset-0 pointer-events-none"></div>

  <div class="w-full max-w-[420px] relative z-10">
    <div class="text-center mb-8">
      <h1 class="text-3xl font-black text-white mb-2">Verifikasi 2 Langkah</h1>
      <p class="text-slate-400 text-sm">{{ $challengeHelp }}</p>
    </div>

    @if(session('status'))
      <div class="mb-5 rounded-2xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-emerald-200 text-sm">
        {{ session('status') }}
      </div>
    @endif

    @if($errors->any())
      <div class="mb-5 rounded-2xl border border-rose-500/20 bg-rose-500/10 p-4 text-rose-200 text-sm">
        {{ $errors->first() }}
      </div>
    @endif

    <div class="challenge-card p-7 sm:p-8">
      <form method="POST" action="{{ route('two-factor.verify') }}" class="space-y-4">
        @csrf

        <div>
          <label class="block text-xs font-semibold text-slate-400 mb-1.5 tracking-wide">KODE {{ strtoupper($challengeTitle) }}</label>
          <input type="text"
                 name="verification_code"
                 inputmode="numeric"
                 maxlength="6"
                 placeholder="6 digit kode"
                 class="challenge-input"
                 autocomplete="one-time-code"
                 required>
        </div>

        <button type="submit" class="challenge-btn">
          Verifikasi & Masuk
        </button>
      </form>

      <div class="mt-5 text-center text-xs text-slate-500">
        {{ $challengeMethod === 'google' ? 'Buka aplikasi Google Authenticator, ambil kode terbaru, lalu masukkan di atas.' : 'Cek pesan verifikasi terbaru di metode yang dipilih, lalu masukkan kodenya di atas.' }}
      </div>
    </div>
  </div>
</div>
@endsection
