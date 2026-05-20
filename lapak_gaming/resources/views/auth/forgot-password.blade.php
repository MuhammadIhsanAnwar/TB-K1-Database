@extends('layouts.app')
@section('title', 'Lupa Password — Lapak Gaming')

@push('styles')
<style>
  .auth-radial-fp {
    background: radial-gradient(ellipse 65% 50% at 50% -5%,
      rgba(37,99,235,0.2) 0%, rgba(249,115,22,0.05) 50%, transparent 100%);
  }
  .auth-card-fp {
    position: relative;
    background: linear-gradient(145deg, #0D1421 0%, #0A1120 100%);
    border-radius: 20px; overflow: hidden;
  }
  .auth-card-fp::before {
    content: '';
    position: absolute; inset: 0; border-radius: 20px; padding: 1px;
    background: linear-gradient(135deg, rgba(37,99,235,0.5) 0%, rgba(37,99,235,0.1) 45%, rgba(249,115,22,0.3) 100%);
    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor; mask-composite: exclude;
    pointer-events: none;
  }
  .auth-card-fp::after {
    content: '';
    position: absolute; top: 0; left: 50%; transform: translateX(-50%);
    width: 70%; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(37,99,235,0.4), transparent);
    pointer-events: none;
  }

  /* ── Icon ring ── */
  .icon-ring {
    width: 64px; height: 64px;
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    position: relative;
  }
  .icon-ring::before {
    content: ''; position: absolute; inset: -4px;
    border-radius: 50%;
    background: conic-gradient(rgba(37,99,235,0.4), rgba(249,115,22,0.2), rgba(37,99,235,0.4));
    animation: ringRotate 4s linear infinite;
  }
  .icon-ring::after {
    content: ''; position: absolute; inset: -1px;
    border-radius: 50%; background: #0D1421;
  }
  .icon-ring .icon-inner {
    position: relative; z-index: 1;
    width: 100%; height: 100%; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    background: rgba(37,99,235,0.15);
    border: 1px solid rgba(37,99,235,0.3);
  }
  @keyframes ringRotate { to { transform: rotate(360deg); } }

  /* ── Input icon ── */
  .input-icon-wrap { position: relative; }
  .input-icon-wrap .input-icon {
    position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
    color: #475569; pointer-events: none; transition: color 0.2s;
    width: 16px; height: 16px;
  }
  .input-icon-wrap:focus-within .input-icon { color: #2563eb; }
  .input-icon-wrap input { padding-left: 2.75rem; }

  /* ── Submit button ── */
  .btn-fp {
    position: relative; overflow: hidden;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    width: 100%; padding: 0.9rem 1.5rem;
    border-radius: 12px;
    font-family: 'Oxanium', sans-serif; font-weight: 700; font-size: 0.9375rem;
    color: white;
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    border: 1px solid rgba(96,165,250,0.3);
    cursor: pointer; transition: all 0.25s;
  }
  .btn-fp::before {
    content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.08), transparent);
    transition: left 0.5s ease;
  }
  .btn-fp:hover { transform: translateY(-1px); box-shadow: 0 0 28px rgba(37,99,235,0.5); }
  .btn-fp:hover::before { left: 100%; }
  .btn-fp:active { transform: scale(0.99); }
  .btn-fp:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

  /* ── Status / success banner ── */
  .status-banner {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 14px 16px; border-radius: 12px;
    background: rgba(16,185,129,0.08);
    border: 1px solid rgba(16,185,129,0.22);
    animation: fadeIn 0.25s ease-out;
  }
  .error-banner {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 12px 14px; border-radius: 12px;
    background: rgba(239,68,68,0.08);
    border: 1px solid rgba(239,68,68,0.22);
    animation: fadeIn 0.25s ease-out;
  }

  /* ── Spinner ── */
  .btn-spinner { display: none; width: 18px; height: 18px; border: 2px solid rgba(255,255,255,0.25); border-top-color: white; border-radius: 50%; animation: spin 0.7s linear infinite; }
  @keyframes spin { to { transform: rotate(360deg); } }

  /* ── Steps ── */
  .fp-step {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 12px; border-radius: 10px;
    background: rgba(255,255,255,0.02);
    border: 1px solid rgba(255,255,255,0.05);
  }
  .fp-step-num {
    width: 24px; height: 24px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    background: rgba(37,99,235,0.15); border: 1px solid rgba(37,99,235,0.3);
    font-family: 'Oxanium', sans-serif; font-weight: 800; font-size: 0.7rem;
    color: #60a5fa; flex-shrink: 0; margin-top: 1px;
  }
</style>
@endpush

@section('content')
<div class="relative min-h-screen flex items-center justify-center py-16 px-4 overflow-hidden">
  <div class="auth-radial-fp absolute inset-0 pointer-events-none"></div>

  <div class="w-full max-w-[420px] animate-fade-up relative z-10">

    {{-- Brand --}}
    <div class="text-center mb-8">
      <a href="{{ route('marketplace.home') }}" class="inline-flex items-center gap-2.5 mb-5 group">
        <div class="relative">
          <div class="absolute inset-0 rounded-xl bg-brand-600/30 blur-md"></div>
          <img src="{{ url('storage/app/public/logo/logo.png') }}" alt="Lapak Gaming"
               class="relative w-11 h-11 rounded-xl object-contain bg-white/5 p-1 border border-white/10">
        </div>
        <span class="font-display font-bold text-xl text-white group-hover:text-brand-300 transition-colors">
          {{ config('app.name', 'Lapak Gaming') }}
        </span>
      </a>
    </div>

    {{-- Card --}}
    <div class="auth-card-fp p-8">

      {{-- Icon --}}
      <div class="flex justify-center mb-6">
        <div class="icon-ring">
          <div class="icon-inner">
            <svg class="w-7 h-7 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
            </svg>
          </div>
        </div>
      </div>

      {{-- Title --}}
      <div class="text-center mb-6">
        <h1 class="font-display text-2xl font-extrabold text-white mb-2 tracking-tight">Lupa Password?</h1>
        <p class="text-slate-400 text-sm leading-relaxed">
          Masukkan alamat email akunmu dan kami akan<br>mengirim link untuk reset password.
        </p>
      </div>

      {{-- Status message --}}
      @if(session('status'))
      <div class="status-banner mb-5">
        <div class="w-8 h-8 rounded-full bg-emerald-500/15 border border-emerald-500/25 flex items-center justify-center flex-shrink-0">
          <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
          </svg>
        </div>
        <div>
          <p class="text-sm font-semibold text-emerald-300 mb-0.5">Email terkirim!</p>
          <p class="text-xs text-emerald-400/80">{{ session('status') }}</p>
        </div>
      </div>
      @endif

      {{-- Error --}}
      @if($errors->any())
      <div class="error-banner mb-5">
        <svg class="w-4 h-4 text-red-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm text-red-300">{{ $errors->first() }}</p>
      </div>
      @endif

      {{-- Form --}}
      <form method="POST" action="{{ route('password.email') }}" id="fp-form">
        @csrf
        <div class="mb-5">
          <label for="email" class="block text-xs font-semibold text-slate-400 mb-1.5 tracking-wide">ALAMAT EMAIL</label>
          <div class="input-icon-wrap">
            <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <input name="email" id="email" type="email"
                   value="{{ old('email') }}"
                   placeholder="nama@email.com"
                   class="input {{ $errors->any() ? 'border-red-500/50' : '' }}"
                   required autocomplete="email">
          </div>
        </div>

        <button type="submit" class="btn-fp" id="fp-btn">
          <span class="btn-text">Kirim Link Reset</span>
          <div class="btn-spinner" id="fp-spinner"></div>
          <svg class="btn-arr w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
          </svg>
        </button>
      </form>

      {{-- Divider --}}
      <div class="relative my-6">
        <div class="absolute inset-0 flex items-center">
          <div class="w-full border-t border-surface-600"></div>
        </div>
        <div class="relative flex justify-center">
          <span class="bg-[#0D1421] px-3 text-xs text-slate-600">Langkah reset password</span>
        </div>
      </div>

      {{-- Steps --}}
      <div class="space-y-2.5">
        <div class="fp-step">
          <div class="fp-step-num">1</div>
          <div>
            <p class="text-xs font-semibold text-slate-300">Masukkan email</p>
            <p class="text-xs text-slate-500 mt-0.5">Pastikan email terdaftar di akun Lapak Gaming-mu</p>
          </div>
        </div>
        <div class="fp-step">
          <div class="fp-step-num">2</div>
          <div>
            <p class="text-xs font-semibold text-slate-300">Cek inbox email</p>
            <p class="text-xs text-slate-500 mt-0.5">Link reset berlaku 60 menit. Cek juga folder Spam.</p>
          </div>
        </div>
        <div class="fp-step">
          <div class="fp-step-num">3</div>
          <div>
            <p class="text-xs font-semibold text-slate-300">Buat password baru</p>
            <p class="text-xs text-slate-500 mt-0.5">Gunakan password kuat yang belum pernah dipakai sebelumnya</p>
          </div>
        </div>
      </div>

      {{-- Back to login --}}
      <div class="text-center mt-6">
        <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-400 hover:text-white transition-colors">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
          </svg>
          Kembali ke halaman masuk
        </a>
      </div>

    </div>{{-- /auth-card-fp --}}
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.getElementById('fp-form').addEventListener('submit', function() {
    const btn = document.getElementById('fp-btn');
    const spinner = document.getElementById('fp-spinner');
    const text = btn.querySelector('.btn-text');
    const arr  = btn.querySelector('.btn-arr');
    if (btn) btn.disabled = true;
    if (spinner) spinner.style.display = 'block';
    if (text) text.textContent = 'Mengirim...';
    if (arr) arr.style.display = 'none';
  });
</script>
@endpush