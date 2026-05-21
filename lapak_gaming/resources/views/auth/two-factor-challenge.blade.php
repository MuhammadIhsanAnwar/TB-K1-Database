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
  $availableMethods = $availableMethods ?? [];
  $challengeMethod = $challengeMethod ?? null;
  if ($challengeMethod !== null && ! in_array($challengeMethod, ['email', 'google'], true)) {
    $challengeMethod = null;
  }
  $challengeTitle = match ($challengeMethod) {
    'email' => 'Email',
    'google' => 'Google Authenticator',
    default => 'Pilih Metode',
  };
  $challengeHelp = match ($challengeMethod) {
    'email' => 'Masukkan kode yang dikirim ke email akun Anda.',
    'google' => 'Masukkan kode dari Google Authenticator untuk melanjutkan login ke akun ' . $user->email . '.',
    default => 'Pilih metode verifikasi 2 langkah yang ingin Anda gunakan.',
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

    @if(session('two_factor_debug_code'))
      <div class="mb-5 rounded-2xl border border-amber-500/20 bg-amber-500/10 p-4 text-amber-200 text-sm">
        <strong>DEBUG CODE:</strong> {{ session('two_factor_debug_code') }}
      </div>
    @endif

    @if($errors->any())
      <div class="mb-5 rounded-2xl border border-rose-500/20 bg-rose-500/10 p-4 text-rose-200 text-sm">
        {{ $errors->first() }}
      </div>
    @endif

    <div class="challenge-card p-7 sm:p-8">
      @if(count($availableMethods) > 1 && ! $challengeMethod)
        <form method="POST" action="{{ route('two-factor.challenge.method') }}" class="space-y-4">
          @csrf

          <div>
            <label class="block text-xs font-semibold text-slate-400 mb-1.5 tracking-wide">Pilih Metode Verifikasi</label>
            <div class="space-y-3">
              @foreach($availableMethods as $methodOption)
                <label class="flex items-center gap-3 rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white cursor-pointer">
                  <input type="radio" name="method" value="{{ $methodOption }}" class="h-4 w-4 text-amber-500 focus:ring-amber-400" required>
                  <span>{{ $methodOption === 'email' ? 'Email' : 'Google Authenticator' }}</span>
                </label>
              @endforeach
            </div>
          </div>

          <button type="submit" class="challenge-btn">
            Pilih Metode
          </button>
        </form>

        <div class="mt-5 text-center text-xs text-slate-500">
          Pilih salah satu metode untuk menerima kode verifikasi, lalu lanjutkan masuk.
        </div>
      @else
        <form method="POST" action="{{ route('two-factor.verify') }}" class="space-y-4">
          @csrf
          @if($challengeMethod)
            <input type="hidden" name="method" value="{{ $challengeMethod }}">
          @endif

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
          @if($challengeMethod === 'google')
            Buka aplikasi Google Authenticator, ambil kode terbaru, lalu masukkan di atas.
          @elseif($challengeMethod === 'email')
            Cek kode terbaru di email akun Anda, lalu masukkan di atas.
          @else
            Pilih metode verifikasi terlebih dahulu.
          @endif
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
