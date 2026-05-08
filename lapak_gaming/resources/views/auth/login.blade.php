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

      <a href="{{ route('google.auth') }}"
         class="mt-4 inline-flex w-full items-center justify-center gap-3 rounded-xl border border-slate-700 bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-900">
        <svg class="h-5 w-5" viewBox="0 0 48 48" aria-hidden="true">
          <path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303C33.654 32.658 29.355 36 24 36c-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.957 3.043l5.657-5.657C34.041 6.053 29.272 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.651-.389-3.917z"/>
          <path fill="#FF3D00" d="M6.306 14.691l6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.957 3.043l5.657-5.657C34.041 6.053 29.272 4 24 4c-7.682 0-14.358 4.337-17.694 10.691z"/>
          <path fill="#4CAF50" d="M24 44c5.143 0 9.86-1.969 13.409-5.178l-6.191-5.238C29.173 35.091 26.763 36 24 36c-5.334 0-9.623-3.323-11.287-7.946l-6.522 5.025C9.48 39.556 16.227 44 24 44z"/>
          <path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 0 1-4.085 5.584l.003-.002 6.191 5.238C36.96 39.101 44 34 44 24c0-1.341-.138-2.651-.389-3.917z"/>
        </svg>
        Masuk dengan Google
      </a>

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