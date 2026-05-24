@extends('layouts.app')
@section('title', 'Masuk — Lapak Gaming')

@push('styles')
<style>
  /* ── Auth page ambient glow ── */
  .auth-radial {
    background: radial-gradient(ellipse 70% 55% at 50% -5%,
      rgba(37,99,235,0.22) 0%,
      rgba(249,115,22,0.06) 60%,
      transparent 100%);
  }

  /* ── Floating particles ── */
  .auth-particle {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
    animation: authFloat var(--dur, 6s) ease-in-out infinite var(--delay, 0s);
    opacity: 0;
    animation-fill-mode: both;
  }
  @keyframes authFloat {
    0%   { transform: translateY(0) scale(1);   opacity: 0; }
    15%  { opacity: var(--op, 0.18); }
    85%  { opacity: var(--op, 0.18); }
    100% { transform: translateY(-120px) scale(0.6); opacity: 0; }
  }

  /* ── Input icon wrapper ── */
  .input-icon-wrap { position: relative; }
  .input-icon-wrap .input-icon {
    position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
    color: #475569; pointer-events: none; transition: color 0.2s;
  }
  .input-icon-wrap:focus-within .input-icon { color: #2563eb; }
  .input-icon-wrap input { padding-left: 2.75rem; }

  /* ── Enhanced glow border card ── */
  .auth-card {
    position: relative;
    background: linear-gradient(145deg, #0D1421 0%, #0A1120 100%);
    border-radius: 20px;
    overflow: hidden;
  }
  .auth-card::before {
    content: '';
    position: absolute; inset: 0;
    border-radius: 20px;
    padding: 1px;
    background: linear-gradient(135deg,
      rgba(37,99,235,0.55) 0%,
      rgba(37,99,235,0.1) 40%,
      rgba(249,115,22,0.35) 100%);
    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor; mask-composite: exclude;
    pointer-events: none; transition: opacity 0.3s;
  }
  .auth-card::after {
    content: '';
    position: absolute; top: -50%; left: 50%; transform: translateX(-50%);
    width: 70%; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(37,99,235,0.5), transparent);
    pointer-events: none;
  }

  /* ── Submit button with pulse ── */
  .btn-auth-submit {
    position: relative; overflow: hidden;
    display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    width: 100%; padding: 0.9rem 1.5rem;
    border-radius: 12px;
    font-family: 'Oxanium', sans-serif;
    font-weight: 700; font-size: 0.9375rem;
    color: white;
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 50%, #1e40af 100%);
    border: 1px solid rgba(96,165,250,0.35);
    transition: all 0.25s; cursor: pointer;
  }
  .btn-auth-submit::before {
    content: '';
    position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.08), transparent);
    transition: left 0.5s ease;
  }
  .btn-auth-submit:hover { transform: translateY(-1px); box-shadow: 0 0 28px rgba(37,99,235,0.55), 0 4px 16px rgba(0,0,0,0.4); }
  .btn-auth-submit:hover::before { left: 100%; }
  .btn-auth-submit:active { transform: translateY(0) scale(0.99); }
  .btn-auth-submit:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

  /* ── Loading spinner ── */
  .btn-spinner {
    display: none; width: 18px; height: 18px;
    border: 2px solid rgba(255,255,255,0.25);
    border-top-color: white; border-radius: 50%;
    animation: spin 0.7s linear infinite;
  }
  @keyframes spin { to { transform: rotate(360deg); } }

  /* ── Google button ── */
  .btn-google {
    display: inline-flex; align-items: center; justify-content: center; gap: 10px;
    width: 100%; padding: 0.8rem 1rem;
    border-radius: 12px;
    border: 1px solid #1E2D45;
    background: #090E1A;
    color: #cbd5e1;
    font-size: 0.875rem; font-weight: 600;
    transition: all 0.2s; cursor: pointer; text-decoration: none;
  }
  .btn-google:hover {
    border-color: rgba(37,99,235,0.45);
    background: #0D1421;
    color: white;
    box-shadow: 0 0 16px rgba(37,99,235,0.2);
  }

  /* ── Divider ── */
  .auth-divider {
    display: flex; align-items: center; gap: 12px;
    color: #334155; font-size: 0.75rem;
  }
  .auth-divider::before, .auth-divider::after {
    content: ''; flex: 1; height: 1px;
    background: linear-gradient(90deg, transparent, #1E2D45, transparent);
  }

  /* ── Checkbox ── */
  .auth-checkbox {
    appearance: none; -webkit-appearance: none;
    width: 16px; height: 16px; border-radius: 5px;
    border: 1.5px solid #334155; background: #090E1A;
    cursor: pointer; transition: all 0.15s; flex-shrink: 0;
    position: relative;
  }
  .auth-checkbox:checked {
    background: #2563eb; border-color: #2563eb;
  }
  .auth-checkbox:checked::after {
    content: ''; position: absolute;
    left: 4px; top: 1.5px; width: 6px; height: 9px;
    border: 2px solid white; border-top: none; border-left: none;
    transform: rotate(43deg);
  }
  .auth-checkbox:focus { outline: none; box-shadow: 0 0 0 3px rgba(37,99,235,0.2); }

  /* ── Eye toggle ── */
  .pwd-toggle {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    color: #475569; background: none; border: none; cursor: pointer;
    padding: 4px; border-radius: 6px; transition: color 0.2s, background 0.2s;
  }
  .pwd-toggle:hover { color: #94a3b8; background: rgba(255,255,255,0.05); }

  /* ── Error banner ── */
  .auth-error {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 12px 14px; border-radius: 12px;
    background: rgba(239,68,68,0.08);
    border: 1px solid rgba(239,68,68,0.22);
    animation: fadeIn 0.25s ease-out;
  }

  /* ── Status banner ── */
  .auth-status {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 14px; border-radius: 12px;
    background: rgba(16,185,129,0.08);
    border: 1px solid rgba(16,185,129,0.22);
    color: #34d399; font-size: 0.85rem;
    animation: fadeIn 0.25s ease-out;
  }
</style>
@endpush

@section('content')
<div class="relative min-h-screen flex items-center justify-center py-16 px-4 overflow-hidden">

  {{-- Ambient background --}}
  <div class="auth-radial absolute inset-0 pointer-events-none"></div>

  {{-- Floating particles --}}
  <div class="auth-particle w-1 h-1 bg-brand-500" style="left:15%;top:80%;--dur:7s;--delay:0s;--op:0.25;"></div>
  <div class="auth-particle w-1.5 h-1.5 bg-accent-400" style="left:80%;top:85%;--dur:9s;--delay:1.5s;--op:0.2;"></div>
  <div class="auth-particle w-1 h-1 bg-brand-400" style="left:45%;top:90%;--dur:8s;--delay:3s;--op:0.22;"></div>
  <div class="auth-particle w-2 h-2 bg-brand-600" style="left:65%;top:75%;--dur:11s;--delay:0.8s;--op:0.15;"></div>
  <div class="auth-particle w-1 h-1 bg-accent-500" style="left:30%;top:88%;--dur:6.5s;--delay:2.2s;--op:0.2;"></div>

  <div class="w-full max-w-[420px] animate-fade-up relative z-10">

    {{-- Brand Header --}}
    <div class="text-center mb-8">
      <a href="{{ route('marketplace.home') }}" class="inline-flex items-center gap-2.5 mb-5 group">
        <div class="relative">
          <div class="absolute inset-0 rounded-xl bg-brand-600/40 blur-md group-hover:blur-lg transition-all duration-300"></div>
          <img src="{{ url('storage/app/public/logo/logo.png') }}"
               alt="Lapak Gaming"
               class="relative w-11 h-11 rounded-xl object-contain surface-weak p-1 border border-white/10">
        </div>
        <span class="font-display font-bold text-xl text-white tracking-wide group-hover:text-brand-300 transition-colors">
          {{ config('app.name', 'Lapak Gaming') }}
        </span>
      </a>
      <h1 class="font-display text-[1.75rem] font-extrabold text-white mb-1.5 tracking-tight">Selamat Datang Kembali</h1>
      <p class="text-slate-400 text-sm">Masuk untuk melanjutkan ke arena gaming-mu</p>
    </div>

    {{-- Card --}}
    <div class="auth-card p-7 sm:p-8">

      {{-- Status flash (setelah register berhasil) --}}
      @if(session('status'))
      <div class="auth-status mb-5">
        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>{{ session('status') }}</span>
      </div>
      @endif

      {{-- Error banner --}}
      @if($errors->any())
      <div class="auth-error mb-5">
        <svg class="w-4 h-4 text-red-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm text-red-300 leading-relaxed">{{ $errors->first() }}</p>
      </div>
      @endif

      {{-- Form --}}
      <form method="POST" action="{{ route('login') }}" class="space-y-4" id="login-form" novalidate>
        @csrf

        {{-- Email --}}
        <div>
          <label for="email" class="block text-xs font-semibold text-slate-400 mb-1.5 tracking-wide">
            ALAMAT EMAIL
          </label>
          <div class="input-icon-wrap">
            <svg class="input-icon w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="input {{ $errors->has('email') ? 'border-red-500/50 focus:border-red-500' : '' }}"
                   placeholder="nama@email.com" required autocomplete="email" />
          </div>
        </div>

        {{-- Password --}}
        <div>
          <div class="flex items-center justify-between mb-1.5">
            <label for="password" class="text-xs font-semibold text-slate-400 tracking-wide">PASSWORD</label>
            <a href="{{ route('password.request') }}" class="text-xs text-brand-400 hover:text-brand-300 transition-colors font-medium hover:underline">
              Lupa password?
            </a>
          </div>
          <div class="input-icon-wrap relative">
            <svg class="input-icon w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            <input id="password" type="password" name="password"
                   class="input pr-11 pl-10" placeholder="••••••••" required autocomplete="current-password" />
            <button type="button" onclick="togglePwd('password', this)" class="pwd-toggle" aria-label="Toggle password visibility">
              <svg class="eye-off w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
              <svg class="eye-on w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
              </svg>
            </button>
          </div>
        </div>

        {{-- Remember me --}}
        <div class="flex items-center gap-2.5">
          <input id="remember" type="checkbox" name="remember" class="auth-checkbox">
          <label for="remember" class="text-sm text-slate-400 cursor-pointer select-none hover:text-slate-300 transition-colors">
            Ingat saya selama 30 hari
          </label>
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-auth-submit mt-1" id="login-btn">
          <span class="btn-text">Masuk Sekarang</span>
          <div class="btn-spinner" id="login-spinner"></div>
          <svg class="btn-arrow w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
          </svg>
        </button>
      </form>

      {{-- Divider --}}
      <div class="auth-divider my-5">
        <span class="text-slate-500 px-1">atau masuk dengan</span>
      </div>

      {{-- Google OAuth --}}
      <a href="{{ route('google.auth') }}" class="btn-google">
        <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 48 48" aria-hidden="true">
          <path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303C33.654 32.658 29.355 36 24 36c-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.957 3.043l5.657-5.657C34.041 6.053 29.272 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.651-.389-3.917z"/>
          <path fill="#FF3D00" d="M6.306 14.691l6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.957 3.043l5.657-5.657C34.041 6.053 29.272 4 24 4c-7.682 0-14.358 4.337-17.694 10.691z"/>
          <path fill="#4CAF50" d="M24 44c5.143 0 9.86-1.969 13.409-5.178l-6.191-5.238C29.173 35.091 26.763 36 24 36c-5.334 0-9.623-3.323-11.287-7.946l-6.522 5.025C9.48 39.556 16.227 44 24 44z"/>
          <path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 01-4.085 5.584l.003-.002 6.191 5.238C36.96 39.101 44 34 44 24c0-1.341-.138-2.651-.389-3.917z"/>
        </svg>
        <span>Lanjutkan dengan Google</span>
      </a>

      {{-- Register link --}}
      <p class="text-center text-sm text-slate-400 mt-6">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-brand-400 hover:text-brand-300 font-semibold transition-colors ml-1 hover:underline">
          Daftar gratis →
        </a>
      </p>

    </div>{{-- /auth-card --}}

    {{-- Footer note --}}
    <p class="text-center text-xs text-slate-600 mt-5">
      Dengan masuk, kamu menyetujui
      <a href="{{ route('terms') }}" class="text-slate-500 hover:text-slate-400 underline">Syarat Layanan</a>
      dan
      <a href="{{ route('privacy') }}" class="text-slate-500 hover:text-slate-400 underline">Kebijakan Privasi</a>
      Lapak Gaming.
    </p>

  </div>{{-- /max-w --}}
</div>
@endsection

@push('scripts')
<script>
  // ── Password toggle dengan animasi icon ──
  function togglePwd(id, btn) {
    const input = document.getElementById(id);
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    btn.querySelector('.eye-off').classList.toggle('hidden', isHidden);
    btn.querySelector('.eye-on').classList.toggle('hidden', !isHidden);
  }

  // ── Loading state saat submit ──
  document.getElementById('login-form').addEventListener('submit', function(e) {
    const btn = document.getElementById('login-btn');
    const spinner = document.getElementById('login-spinner');
    const text = btn.querySelector('.btn-text');
    const arrow = btn.querySelector('.btn-arrow');

    btn.disabled = true;
    if (spinner) spinner.style.display = 'block';
    if (text) text.textContent = 'Memeriksa...';
    if (arrow) arrow.style.display = 'none';
  });
</script>
@endpush