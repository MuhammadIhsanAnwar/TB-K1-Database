@extends('layouts.app')
@section('title', 'Daftar — Lapak Gaming')

@push('styles')
<style>
  /* ── Register ambient ── */
  .register-radial {
    background:
      radial-gradient(ellipse 50% 40% at 15% 10%,  rgba(37,99,235,0.16) 0%, transparent 70%),
      radial-gradient(ellipse 40% 35% at 85% 15%,  rgba(249,115,22,0.10) 0%, transparent 65%),
      radial-gradient(ellipse 45% 30% at 50% 100%, rgba(37,99,235,0.08) 0%, transparent 70%);
  }

  /* ── Info panel ── */
  .info-panel {
    background: linear-gradient(145deg, #0D1421 0%, #0F1928 100%);
    border: 1px solid rgba(37,99,235,0.2);
    border-radius: 20px;
    position: relative; overflow: hidden;
  }
  .info-panel::before {
    content: '';
    position: absolute; top: 0; right: 0; bottom: 0; left: 0;
    background: linear-gradient(135deg, rgba(37,99,235,0.05) 0%, transparent 60%);
    pointer-events: none;
  }

  /* ── Form panel ── */
  .form-panel {
    background: linear-gradient(145deg, #0D1421 0%, #0A1120 100%);
    border: 1px solid rgba(37,99,235,0.18);
    border-radius: 20px;
    position: relative; overflow: hidden;
  }
  .form-panel::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(37,99,235,0.5), rgba(249,115,22,0.3), transparent);
    pointer-events: none;
  }

  /* ── Feature item ── */
  .feature-item {
    display: flex; gap: 14px; align-items: flex-start;
    padding: 14px 16px; border-radius: 12px;
    background: rgba(255,255,255,0.025);
    border: 1px solid rgba(255,255,255,0.06);
    transition: border-color 0.2s, background 0.2s;
  }
  .feature-item:hover {
    background: rgba(37,99,235,0.05);
    border-color: rgba(37,99,235,0.2);
  }
  .feature-icon {
    width: 36px; height: 36px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }

  /* ── Step badges ── */
  .step-badge {
    display: inline-flex; align-items: center; justify-content: center;
    width: 22px; height: 22px; border-radius: 50%;
    font-size: 0.7rem; font-weight: 800;
    font-family: 'Oxanium', sans-serif;
  }

  /* ── Password strength bar ── */
  .strength-bar-track {
    height: 4px; border-radius: 99px;
    background: #1E2D45; overflow: hidden; margin-top: 8px;
  }
  .strength-bar-fill {
    height: 100%; border-radius: 99px;
    transition: width 0.4s ease, background-color 0.4s ease;
    width: 0%;
  }

  /* ── Password rules ── */
  .pwd-rule {
    display: flex; align-items: center; gap: 8px;
    font-size: 0.75rem; color: #64748b;
    transition: color 0.2s;
  }
  .pwd-rule .rule-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: #334155; flex-shrink: 0;
    transition: background-color 0.2s, transform 0.2s;
  }
  .pwd-rule.ok .rule-dot { background: #10b981; transform: scale(1.2); }
  .pwd-rule.ok { color: #34d399; }
  .pwd-rule.fail .rule-dot { background: #ef4444; }
  .pwd-rule.fail { color: #f87171; }

  /* ── Match indicator ── */
  .match-ok  { color: #34d399; font-size: 0.75rem; display: none; align-items: center; gap: 6px; margin-top: 6px; }
  .match-err { color: #f87171; font-size: 0.75rem; display: none; align-items: center; gap: 6px; margin-top: 6px; }

  /* ── Photo preview ring ── */
  .photo-ring {
    border: 2px solid #1E2D45;
    border-radius: 16px; overflow: hidden;
    width: 120px; height: 120px;
    transition: border-color 0.3s;
    background: #090E1A;
    position: relative;
  }
  .photo-ring.has-photo { border-color: rgba(37,99,235,0.5); }
  .photo-ring img { width: 100%; height: 100%; object-fit: cover; }

  /* ── Upload zone ── */
  .upload-zone {
    border: 1.5px dashed #1E2D45;
    border-radius: 12px; padding: 14px;
    text-align: center; cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
  }
  .upload-zone:hover, .upload-zone.drag-over {
    border-color: rgba(37,99,235,0.5);
    background: rgba(37,99,235,0.04);
  }

  /* ── Input icon (shared with login) ── */
  .input-icon-wrap { position: relative; }
  .input-icon-wrap .input-icon {
    position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
    color: #475569; pointer-events: none; transition: color 0.2s;
    width: 16px; height: 16px;
  }
  .input-icon-wrap:focus-within .input-icon { color: #2563eb; }
  .input-icon-wrap .has-icon { padding-left: 2.75rem; }

  /* ── Eye toggle ── */
  .pwd-toggle {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    color: #475569; background: none; border: none; cursor: pointer;
    padding: 4px; border-radius: 6px; transition: color 0.2s, background 0.2s;
  }
  .pwd-toggle:hover { color: #94a3b8; background: rgba(255,255,255,0.05); }

  /* ── Section label ── */
  .form-section-label {
    font-size: 0.65rem; font-weight: 700; letter-spacing: 0.12em;
    text-transform: uppercase; color: #475569;
    display: flex; align-items: center; gap: 8px;
    margin-bottom: 12px;
  }
  .form-section-label::after {
    content: ''; flex: 1; height: 1px;
    background: linear-gradient(90deg, #1E2D45, transparent);
  }

  /* ── Submit button ── */
  .btn-register {
    position: relative; overflow: hidden;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    width: 100%; padding: 0.9rem 1.5rem;
    border-radius: 12px;
    font-family: 'Oxanium', sans-serif; font-weight: 700; font-size: 0.9375rem;
    color: white;
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 45%, #f97316 100%);
    background-size: 200% 100%; background-position: 0% 0%;
    border: none; cursor: pointer;
    transition: background-position 0.4s ease, box-shadow 0.25s, transform 0.2s;
  }
  .btn-register:hover {
    background-position: 100% 0%;
    box-shadow: 0 0 28px rgba(37,99,235,0.45), 0 4px 16px rgba(0,0,0,0.35);
    transform: translateY(-1px);
  }
  .btn-register:active { transform: scale(0.99); }
  .btn-register:disabled { opacity: 0.55; cursor: not-allowed; transform: none; }

  /* ── Google button ── */
  .btn-google-reg {
    display: inline-flex; align-items: center; justify-content: center; gap: 10px;
    width: 100%; padding: 0.8rem 1rem;
    border-radius: 12px; border: 1px solid #1E2D45;
    background: #090E1A; color: #cbd5e1;
    font-size: 0.875rem; font-weight: 600;
    transition: all 0.2s; cursor: pointer; text-decoration: none;
  }
  .btn-google-reg:hover {
    border-color: rgba(37,99,235,0.45); background: #0D1421; color: white;
  }

  /* ── Auth divider ── */
  .auth-divider {
    display: flex; align-items: center; gap: 12px;
    color: #334155; font-size: 0.75rem;
  }
  .auth-divider::before, .auth-divider::after {
    content: ''; flex: 1; height: 1px;
    background: linear-gradient(90deg, transparent, #1E2D45, transparent);
  }

  /* ── Spinner ── */
  .btn-spinner { display: none; width: 18px; height: 18px; border: 2px solid rgba(255,255,255,0.25); border-top-color: white; border-radius: 50%; animation: spin 0.7s linear infinite; }
  @keyframes spin { to { transform: rotate(360deg); } }

  /* ── Checkbox ── */
  .auth-checkbox {
    appearance: none; -webkit-appearance: none;
    width: 16px; height: 16px; border-radius: 5px;
    border: 1.5px solid #334155; background: #090E1A;
    cursor: pointer; transition: all 0.15s; flex-shrink: 0;
    position: relative;
  }
  .auth-checkbox:checked { background: #2563eb; border-color: #2563eb; }
  .auth-checkbox:checked::after {
    content: ''; position: absolute; left: 4px; top: 1.5px;
    width: 6px; height: 9px;
    border: 2px solid white; border-top: none; border-left: none;
    transform: rotate(43deg);
  }
</style>
@endpush

@section('content')
<div class="relative min-h-screen py-12 px-4 overflow-hidden">

  {{-- Ambient background --}}
  <div class="register-radial absolute inset-0 pointer-events-none"></div>

  <div class="relative max-w-6xl mx-auto z-10">

    {{-- Top brand bar --}}
    <div class="flex items-center justify-between mb-8 animate-fade-up">
      <a href="{{ route('marketplace.home') }}" class="inline-flex items-center gap-2.5 group">
        <div class="relative">
          <div class="absolute inset-0 rounded-xl bg-brand-600/30 blur-md"></div>
          <img src="{{ url('storage/app/public/logo/logo.png') }}" alt="Lapak Gaming"
               class="relative w-9 h-9 rounded-xl object-contain bg-white/5 p-1 border border-white/10">
        </div>
        <span class="font-display font-bold text-lg text-white">{{ config('app.name', 'Lapak Gaming') }}</span>
      </a>
      <a href="{{ route('login') }}" class="text-sm text-slate-400 hover:text-white transition-colors flex items-center gap-1.5">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
        Sudah punya akun? Masuk
      </a>
    </div>

    {{-- Main grid --}}
    <div class="grid lg:grid-cols-[1fr_1.3fr] gap-6 animate-fade-up" style="animation-delay:0.05s">

      {{-- ── Left: Info Panel ── --}}
      <div class="info-panel p-7 flex flex-col justify-between">
        <div>
          {{-- Badge --}}
          <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full mb-6"
               style="background:rgba(37,99,235,0.12);border:1px solid rgba(37,99,235,0.25);">
            <span class="w-1.5 h-1.5 rounded-full bg-brand-400 animate-pulse"></span>
            <span class="text-xs font-bold text-brand-300 uppercase tracking-widest">Bergabung Sekarang</span>
          </div>

          <h1 class="font-display text-4xl font-extrabold text-white leading-tight mb-4 tracking-tight">
            Bergabung dengan<br>
            <span
  class="inline-block bg-clip-text text-transparent"
  style="
    background-image: linear-gradient(135deg,#60a5fa,#fb923c);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;">
  Jutaan Gamer
</span>
          </h1>
          <p class="text-slate-400 text-sm leading-relaxed mb-8">
            Marketplace top-up, item, akun & voucher game terpercaya. Transaksi aman, cepat, dan terjamin.
          </p>

          {{-- Features --}}
          <div class="space-y-3">
            <div class="feature-item">
              <div class="feature-icon" style="background:rgba(37,99,235,0.12);border:1px solid rgba(37,99,235,0.2);">
                <svg class="w-4 h-4 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
              </div>
              <div>
                <p class="text-sm font-semibold text-white mb-0.5">Foto Profil Custom</p>
                <p class="text-xs text-slate-500">Upload JPG, PNG, atau WEBP hingga 5MB. Crop langsung di browser.</p>
              </div>
            </div>

            <div class="feature-item">
              <div class="feature-icon" style="background:rgba(16,185,129,0.12);border:1px solid rgba(16,185,129,0.2);">
                <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
              </div>
              <div>
                <p class="text-sm font-semibold text-white mb-0.5">Akun Terverifikasi</p>
                <p class="text-xs text-slate-500">Verifikasi email untuk keamanan akun dan akses penuh semua fitur.</p>
              </div>
            </div>

            <div class="feature-item">
              <div class="feature-icon" style="background:rgba(249,115,22,0.12);border:1px solid rgba(249,115,22,0.2);">
                <svg class="w-4 h-4 text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
              </div>
              <div>
                <p class="text-sm font-semibold text-white mb-0.5">Transaksi Instan</p>
                <p class="text-xs text-slate-500">Browse, beli, dan checkout dalam hitungan menit. Dompet digital terintegrasi.</p>
              </div>
            </div>

            <div class="feature-item">
              <div class="feature-icon" style="background:rgba(139,92,246,0.12);border:1px solid rgba(139,92,246,0.2);">
                <svg class="w-4 h-4 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
              </div>
              <div>
                <p class="text-sm font-semibold text-white mb-0.5">Komunitas Gamer</p>
                <p class="text-xs text-slate-500">Bergabung dengan ribuan seller dan buyer aktif setiap harinya.</p>
              </div>
            </div>
          </div>
        </div>

        {{-- Stats strip --}}
        <div class="grid grid-cols-3 gap-3 mt-8 pt-6 border-t border-white/5">
          <div class="text-center">
            <p class="font-display text-xl font-bold text-white">50K+</p>
            <p class="text-xs text-slate-500 mt-0.5">Member Aktif</p>
          </div>
          <div class="text-center border-x border-white/5">
            <p class="font-display text-xl font-bold text-white">10K+</p>
            <p class="text-xs text-slate-500 mt-0.5">Produk Game</p>
          </div>
          <div class="text-center">
            <p class="font-display text-xl font-bold text-white">99%</p>
            <p class="text-xs text-slate-500 mt-0.5">Transaksi Aman</p>
          </div>
        </div>
      </div>{{-- /info-panel --}}

      {{-- ── Right: Form Panel ── --}}
      <div class="form-panel p-7">
        <div class="mb-6">
          <h2 class="font-display text-2xl font-extrabold text-white mb-1">Buat Akun Baru</h2>
          <p class="text-sm text-slate-400">Lengkapi data diri untuk melanjutkan</p>
        </div>

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-5" id="register-form" data-register-form novalidate>
          @csrf

          @php
            $inputClass = 'input w-full';
          @endphp

          {{-- ── SECTION: Foto Profil ── --}}
          <div>
            <div class="form-section-label">Foto Profil</div>
            <div class="flex items-start gap-5">
              {{-- Preview --}}
              <div>
                <div class="photo-ring" id="photo-ring">
                  <img src="https://ui-avatars.com/api/?name=?&background=0D1421&color=2563eb&size=240&bold=true"
                       alt="Preview" id="photo-preview" class="w-full h-full object-cover">
                </div>
                <p class="text-xs text-slate-600 text-center mt-2 w-[120px]">Foto profil</p>
              </div>

              {{-- Upload zone --}}
              <div class="flex-1 space-y-2">
                <label class="upload-zone block" for="profile_photo_input">
                  <svg class="w-6 h-6 text-slate-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                  </svg>
                  <p class="text-xs text-slate-500 font-medium">Klik atau drag foto ke sini</p>
                  <p class="text-xs text-slate-600 mt-1">JPG, PNG, WEBP — max 5MB</p>
                </label>
                <input id="profile_photo_input" name="profile_photo" type="file" accept="image/*"
                       class="hidden" data-photo-input required>
                @error('profile_photo')
                  <p class="text-xs text-red-400 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                  </p>
                @enderror
              </div>
            </div>
          </div>

          {{-- ── SECTION: Data Pribadi ── --}}
          <div>
            <div class="form-section-label">Data Pribadi</div>
            <div class="space-y-4">

              {{-- Nama --}}
              <div>
                <label for="name" class="block text-xs font-semibold text-slate-400 mb-1.5 tracking-wide">NAMA LENGKAP</label>
                <div class="input-icon-wrap">
                  <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                  </svg>
                  <input id="name" name="name" type="text" value="{{ old('name') }}"
                         placeholder="Masukkan nama lengkap"
                         class="{{ $inputClass }} has-icon" required>
                </div>
                @error('name')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
              </div>

              {{-- Gender & Birth Date --}}
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label for="gender" class="block text-xs font-semibold text-slate-400 mb-1.5 tracking-wide">JENIS KELAMIN</label>
                  <select id="gender" name="gender" class="{{ $inputClass }}" required>
                    <option value="">Pilih...</option>
                    <option value="male"   {{ old('gender') === 'male'   ? 'selected' : '' }}>Laki-laki</option>
                    <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Perempuan</option>
                    <option value="other"  {{ old('gender') === 'other'  ? 'selected' : '' }}>Lainnya</option>
                  </select>
                  @error('gender')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                  <label for="birth_date" class="block text-xs font-semibold text-slate-400 mb-1.5 tracking-wide">TGL. LAHIR</label>
                  <input id="birth_date" name="birth_date" type="date" value="{{ old('birth_date') }}"
                         class="{{ $inputClass }}" required>
                  @error('birth_date')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
                </div>
              </div>

              {{-- Phone --}}
              <div>
                <label for="phone" class="block text-xs font-semibold text-slate-400 mb-1.5 tracking-wide">NOMOR TELEPON</label>
                <div class="input-icon-wrap">
                  <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                  </svg>
                  <input id="phone" name="phone" type="tel" value="{{ old('phone') }}"
                         placeholder="08xxxxxxxxxx"
                         class="{{ $inputClass }} has-icon" required>
                </div>
                @error('phone')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
              </div>
            </div>
          </div>

          {{-- ── SECTION: Akun ── --}}
          <div>
            <div class="form-section-label">Data Akun</div>
            <div class="space-y-4">

              {{-- Email --}}
              <div>
                <label for="email" class="block text-xs font-semibold text-slate-400 mb-1.5 tracking-wide">EMAIL</label>
                <div class="input-icon-wrap">
                  <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                  </svg>
                  <input id="email" name="email" type="email" value="{{ old('email') }}"
                         placeholder="nama@email.com"
                         class="{{ $inputClass }} has-icon" required>
                </div>
                @error('email')<p class="mt-1 text-xs text-red-400">{{ $message }}</p>@enderror
              </div>

              {{-- Password --}}
              <div>
                <label for="password" class="block text-xs font-semibold text-slate-400 mb-1.5 tracking-wide">PASSWORD</label>
                <div class="input-icon-wrap relative">
                  <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                  </svg>
                  <input id="password" name="password" type="password"
                         placeholder="Minimal 8 karakter"
                         class="{{ $inputClass }} has-icon pr-11" required>
                  <button type="button" onclick="togglePwd('password', this)" class="pwd-toggle" aria-label="Toggle">
                    <svg class="eye-off w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg class="eye-on w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                  </button>
                </div>

                {{-- Strength bar --}}
                <div class="strength-bar-track">
                  <div class="strength-bar-fill" id="strength-bar"></div>
                </div>
                <p class="text-xs mt-1.5" id="strength-label" style="color:#475569;">Belum ada password</p>

                {{-- Rules --}}
                <div class="grid grid-cols-2 gap-1.5 mt-3" id="password-requirements">
                  <div class="pwd-rule" data-rule="length">
                    <span class="rule-dot"></span> Min. 8 karakter
                  </div>
                  <div class="pwd-rule" data-rule="lower">
                    <span class="rule-dot"></span> Huruf kecil (a-z)
                  </div>
                  <div class="pwd-rule" data-rule="upper">
                    <span class="rule-dot"></span> Huruf besar (A-Z)
                  </div>
                  <div class="pwd-rule" data-rule="number">
                    <span class="rule-dot"></span> Angka (0-9)
                  </div>
                  <div class="pwd-rule col-span-2" data-rule="symbol">
                    <span class="rule-dot"></span> Simbol (!@#$%...)
                  </div>
                </div>
              </div>

              {{-- Confirm Password --}}
              <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-slate-400 mb-1.5 tracking-wide">KONFIRMASI PASSWORD</label>
                <div class="input-icon-wrap relative">
                  <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                  </svg>
                  <input id="password_confirmation" name="password_confirmation" type="password"
                         placeholder="Ulangi password"
                         class="{{ $inputClass }} has-icon pr-11" required>
                  <button type="button" onclick="togglePwd('password_confirmation', this)" class="pwd-toggle" aria-label="Toggle">
                    <svg class="eye-off w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg class="eye-on w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                  </button>
                </div>
                <div class="match-ok" id="match-ok">
                  <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                  Password cocok
                </div>
                <div class="match-err" id="match-err">
                  <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                  Password tidak cocok
                </div>
                <p class="hidden mt-1 text-xs text-red-400" id="password-match-error">Konfirmasi password tidak cocok.</p>
              </div>

              {{-- Terms checkbox --}}
              <div class="flex items-start gap-2.5">
                <input type="checkbox" id="terms_agree" class="auth-checkbox mt-0.5" required>
                <label for="terms_agree" class="text-xs text-slate-400 cursor-pointer leading-relaxed">
                  Saya menyetujui
                  <a href="#" class="text-brand-400 hover:underline">Syarat & Ketentuan</a>
                  serta
                  <a href="#" class="text-brand-400 hover:underline">Kebijakan Privasi</a>
                  Lapak Gaming
                </label>
              </div>

            </div>
          </div>

          {{-- Submit --}}
          <button type="submit" class="btn-register" id="register-btn">
            <span class="btn-text">Buat Akun Sekarang</span>
            <div class="btn-spinner" id="reg-spinner"></div>
            <svg class="btn-arr w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
          </button>

          {{-- Divider --}}
          <div class="auth-divider">
            <span class="text-slate-500 px-1">atau daftar dengan</span>
          </div>

          {{-- Google --}}
          <a href="{{ route('google.auth') }}" class="btn-google-reg">
            <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 48 48" aria-hidden="true">
              <path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303C33.654 32.658 29.355 36 24 36c-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.957 3.043l5.657-5.657C34.041 6.053 29.272 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.651-.389-3.917z"/>
              <path fill="#FF3D00" d="M6.306 14.691l6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.957 3.043l5.657-5.657C34.041 6.053 29.272 4 24 4c-7.682 0-14.358 4.337-17.694 10.691z"/>
              <path fill="#4CAF50" d="M24 44c5.143 0 9.86-1.969 13.409-5.178l-6.191-5.238C29.173 35.091 26.763 36 24 36c-5.334 0-9.623-3.323-11.287-7.946l-6.522 5.025C9.48 39.556 16.227 44 24 44z"/>
              <path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 01-4.085 5.584l.003-.002 6.191 5.238C36.96 39.101 44 34 44 24c0-1.341-.138-2.651-.389-3.917z"/>
            </svg>
            <span>Daftar dengan Google</span>
          </a>

          <p class="text-center text-xs text-slate-500">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-brand-400 hover:text-brand-300 font-semibold transition-colors hover:underline ml-1">Masuk sekarang</a>
          </p>

        </form>
      </div>{{-- /form-panel --}}

    </div>{{-- /grid --}}
  </div>{{-- /max-w --}}
</div>
@endsection

@push('scripts')
<script>
(function () {
  // ── Photo preview ──
  const photoInput = document.querySelector('[data-photo-input]');
  const photoPreview = document.getElementById('photo-preview');
  const photoRing = document.getElementById('photo-ring');
  const uploadZone = document.querySelector('.upload-zone');

  if (photoInput && photoPreview) {
    photoInput.addEventListener('change', function () {
      const file = this.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = function (e) {
        photoPreview.src = e.target.result;
        if (photoRing) photoRing.classList.add('has-photo');
      };
      reader.readAsDataURL(file);
    });
  }

  // Drag over visual feedback
  if (uploadZone && photoInput) {
    uploadZone.addEventListener('dragover', (e) => { e.preventDefault(); uploadZone.classList.add('drag-over'); });
    uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('drag-over'));
    uploadZone.addEventListener('drop', (e) => {
      e.preventDefault(); uploadZone.classList.remove('drag-over');
      const files = e.dataTransfer.files;
      if (files.length) {
        photoInput.files = files;
        photoInput.dispatchEvent(new Event('change'));
      }
    });
  }

  // ── Password toggle ──
  window.togglePwd = function(id, btn) {
    const input = document.getElementById(id);
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    btn.querySelector('.eye-off').classList.toggle('hidden', isHidden);
    btn.querySelector('.eye-on').classList.toggle('hidden', !isHidden);
  };

  // ── Password strength ──
  const pwd = document.getElementById('password');
  const pwdConfirm = document.getElementById('password_confirmation');
  const strengthBar = document.getElementById('strength-bar');
  const strengthLabel = document.getElementById('strength-label');

  function checkPasswordRules(value) {
    return {
      length: value.length >= 8,
      lower:  /[a-z]/.test(value),
      upper:  /[A-Z]/.test(value),
      number: /[0-9]/.test(value),
      symbol: /[^A-Za-z0-9]/.test(value),
    };
  }

  function updateStrength(value) {
    const rules = checkPasswordRules(value);
    const passed = Object.values(rules).filter(Boolean).length;

    // Update rule items
    const ruleItems = document.querySelectorAll('#password-requirements .pwd-rule');
    ruleItems.forEach((li) => {
      const rule = li.getAttribute('data-rule');
      li.classList.toggle('ok',   !!rules[rule]);
      li.classList.toggle('fail', value.length > 0 && !rules[rule]);
    });

    // Strength bar
    if (!strengthBar) return;
    const configs = [
      { w: '0%',   color: '#1E2D45', label: 'Belum ada password',  labelColor: '#475569' },
      { w: '20%',  color: '#ef4444', label: 'Sangat lemah',         labelColor: '#f87171' },
      { w: '40%',  color: '#f97316', label: 'Lemah',                labelColor: '#fb923c' },
      { w: '60%',  color: '#eab308', label: 'Cukup',                labelColor: '#facc15' },
      { w: '80%',  color: '#3b82f6', label: 'Kuat',                 labelColor: '#60a5fa' },
      { w: '100%', color: '#10b981', label: '✓ Sangat kuat',        labelColor: '#34d399' },
    ];
    const c = configs[passed] || configs[0];
    strengthBar.style.width = c.w;
    strengthBar.style.backgroundColor = c.color;
    if (strengthLabel) { strengthLabel.textContent = c.label; strengthLabel.style.color = c.labelColor; }
  }

  if (pwd) pwd.addEventListener('input', () => {
    updateStrength(pwd.value);
    checkMatch();
  });

  // ── Password match ──
  function checkMatch() {
    if (!pwdConfirm || !pwd || !pwdConfirm.value) {
      document.getElementById('match-ok').style.display = 'none';
      document.getElementById('match-err').style.display = 'none';
      return;
    }
    const match = pwd.value === pwdConfirm.value;
    document.getElementById('match-ok').style.display  = match ? 'flex' : 'none';
    document.getElementById('match-err').style.display = match ? 'none' : 'flex';
  }
  if (pwdConfirm) pwdConfirm.addEventListener('input', checkMatch);

  // ── Form submit ──
  const form = document.querySelector('[data-register-form]');
  if (form) {
    form.addEventListener('submit', function (e) {
      const rules = checkPasswordRules(pwd?.value || '');
      const allOk = Object.values(rules).every(Boolean);
      const passwordMismatch = pwdConfirm && pwd && pwd.value !== pwdConfirm.value;
      const termsCheck = document.getElementById('terms_agree');
      const matchErrEl = document.getElementById('password-match-error');

      if (matchErrEl) matchErrEl.classList.toggle('hidden', !passwordMismatch);

      if (!allOk || passwordMismatch) {
        e.preventDefault();
        if (!allOk) {
          showToast('Password belum memenuhi semua syarat keamanan.', 'error');
        } else {
          showToast('Konfirmasi password tidak cocok.', 'error');
        }
        return;
      }

      if (termsCheck && !termsCheck.checked) {
        e.preventDefault();
        showToast('Kamu harus menyetujui syarat & ketentuan terlebih dahulu.', 'error');
        return;
      }

      // Loading state
      const btn = document.getElementById('register-btn');
      const spinner = document.getElementById('reg-spinner');
      const text = btn?.querySelector('.btn-text');
      const arrow = btn?.querySelector('.btn-arr');
      if (btn) btn.disabled = true;
      if (spinner) spinner.style.display = 'block';
      if (text) text.textContent = 'Membuat akun...';
      if (arrow) arrow.style.display = 'none';
    });
  }

  updateStrength('');
})();
</script>
@endpush