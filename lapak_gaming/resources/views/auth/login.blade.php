@extends('layouts.app')
@section('title', 'Masuk — Lapak Gaming')

@push('styles')
<style>
  .auth-glow { background:radial-gradient(ellipse 60% 60% at 50% 0%, rgba(37,99,235,0.2), transparent 70%); }
</style>
@endpush

@section('content')
<div class="relative min-h-screen flex items-center justify-center py-16 px-4">
  <div class="auth-glow absolute inset-0 pointer-events-none"></div>

  <div class="w-full max-w-md animate-fade-up">

    {{-- Logo --}}
    <div class="text-center mb-8">
      <a href="{{ route('marketplace.home') }}" class="inline-flex items-center gap-2.5 mb-5">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center"
             style="background:linear-gradient(135deg,#2563eb,#f97316);box-shadow:0 0 20px rgba(37,99,235,0.4);">
          <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
          </svg>
        </div>
        <span class="font-display font-bold text-xl text-white">{{ config('app.name', 'Lapak Gaming') }}</span>
      </a>
      <h1 class="font-display text-2xl font-extrabold text-white mb-2">Selamat Datang Kembali</h1>
      <p class="text-slate-400 text-sm">Masuk untuk melanjutkan ke dashboard gaming-mu</p>
    </div>

    {{-- Card --}}
    <div class="card-glow-border p-7 sm:p-8">

      {{-- Error Alert --}}
      @if($errors->any())
      <div class="flex items-start gap-3 rounded-xl p-3.5 mb-6" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);">
        <svg class="w-4 h-4 text-red-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div class="text-sm text-red-300">{{ $errors->first() }}</div>
      </div>
      @endif

      <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        {{-- Email --}}
        <div>
          <label for="email" class="block text-xs font-medium text-slate-400 mb-1.5">Email</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}"
                 class="input" placeholder="nama@email.com" required autocomplete="email" />
        </div>

        {{-- Password --}}
        <div>
          <div class="flex items-center justify-between mb-1.5">
            <label for="password" class="text-xs font-medium text-slate-400">Password</label>
            <a href="{{ route('password.request') }}" class="text-xs text-brand-400 hover:text-brand-300 transition-colors">Lupa password?</a>
          </div>
          <div class="relative">
            <input id="password" type="password" name="password"
                   class="input pr-10" placeholder="••••••••" required autocomplete="current-password" />
            <button type="button" onclick="togglePwd('password', this)"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition-colors">
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </button>
          </div>
        </div>

        {{-- Remember --}}
        <div class="flex items-center gap-2">
          <input id="remember" type="checkbox" name="remember" class="w-4 h-4 rounded" style="accent-color:#2563eb;">
          <label for="remember" class="text-sm text-slate-400 cursor-pointer select-none">Ingat saya</label>
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-primary w-full py-3.5 rounded-xl text-base mt-2">
          Masuk Sekarang
        </button>
      </form>

      {{-- Divider --}}
      <div class="flex items-center gap-3 my-5">
        <div class="flex-1 h-px" style="background:#1E2D45;"></div>
        <span class="text-xs text-slate-500">atau</span>
        <div class="flex-1 h-px" style="background:#1E2D45;"></div>
      </div>

      {{-- Register link --}}
      <div class="text-center text-sm text-slate-400">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-brand-400 hover:text-brand-300 font-semibold transition-colors ml-1">Daftar sekarang →</a>
      </div>
    </div>

    {{-- Seller link --}}
    <div class="text-center mt-5">
      <a href="{{ route('register.seller') }}" class="text-xs text-slate-500 hover:text-slate-300 transition-colors">
        Ingin berjualan? <span class="text-accent-400">Daftar sebagai Seller →</span>
      </a>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function togglePwd(id, btn) {
  const input = document.getElementById(id);
  input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
@endpush