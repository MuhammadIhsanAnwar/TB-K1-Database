@extends('layouts.app')

@section('title', 'Kelola Banner — Admin')

@push('styles')
<style>
    /* ── Ambient control panel ───────────────────────────────── */
    .dashboard-transparent {
        background:
            radial-gradient(circle at top left, rgba(245, 158, 11, 0.10), transparent 26%),
            radial-gradient(circle at bottom right, rgba(96, 165, 250, 0.08), transparent 24%),
            linear-gradient(180deg, rgba(5, 9, 16, 0.82), rgba(5, 9, 16, 0.96));
    }
    
    .panel-card-glass {
        position: relative;
        overflow: hidden;
        background: linear-gradient(145deg, rgba(13, 20, 33, 0.92), rgba(8, 13, 24, 0.92)) !important;
        backdrop-filter: blur(24px) saturate(160%);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 20px 55px rgba(0, 0, 0, 0.48);
    }

    .panel-card-glass::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(96, 165, 250, 0.08), transparent 45%, rgba(245, 158, 11, 0.06));
        pointer-events: none;
    }

    .panel-card-glass::after {
        content: '';
        position: absolute;
        inset: 1px;
        border-radius: inherit;
        border: 1px solid rgba(255, 255, 255, 0.04);
        pointer-events: none;
    }

    .banner-form-shell {
        box-shadow: 0 24px 70px rgba(0, 0, 0, 0.45);
    }

    .banner-form-shell:hover {
        transform: translateY(-1px);
    }

    .banner-header-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border-radius: 999px;
        padding: 0.45rem 0.85rem;
        border: 1px solid rgba(245, 158, 11, 0.18);
        background: rgba(245, 158, 11, 0.08);
        color: #fcd34d;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.05);
    }

    .input-glass {
        background: rgba(5, 9, 16, 0.62) !important;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 14px;
        min-height: 48px;
        color: #f8fafc;
        transition: border-color 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s cubic-bezier(0.4, 0, 0.2, 1), transform 0.2s;
    }
    .input-glass:focus {
        border-color: rgba(245, 158, 11, 0.55) !important;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.12), 0 0 14px rgba(245, 158, 11, 0.18);
        transform: translateY(-1px);
    }
    .input-glass:hover {
        border-color: rgba(96, 165, 250, 0.22);
    }
    .input-glass::placeholder {
        color: #64748b;
    }
    .input-glass option {
        background: #0d1421;
        color: #e2e8f0;
    }

    select.input-glass {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        padding-right: 2.75rem;
        background-image:
            linear-gradient(45deg, transparent 50%, #60a5fa 50%),
            linear-gradient(135deg, #60a5fa 50%, transparent 50%),
            linear-gradient(to right, rgba(255,255,255,0.04), rgba(255,255,255,0.02));
        background-position:
            calc(100% - 18px) calc(50% - 2px),
            calc(100% - 13px) calc(50% - 2px),
            0 0;
        background-size: 5px 5px, 5px 5px, 100% 100%;
        background-repeat: no-repeat;
        cursor: pointer;
    }

    .form-help-text {
        color: #94a3b8;
        line-height: 1.65;
    }

    .banner-submit-btn,
    .banner-secondary-btn,
    .banner-danger-btn {
        position: relative;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease, background-color 0.2s ease;
    }

    .banner-submit-btn:hover,
    .banner-secondary-btn:hover,
    .banner-danger-btn:hover {
        transform: translateY(-1px);
    }

    .banner-submit-btn {
        box-shadow: 0 14px 26px rgba(245, 158, 11, 0.18);
    }

    .banner-secondary-btn {
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .banner-danger-btn {
        box-shadow: 0 12px 24px rgba(248, 113, 113, 0.16);
    }

    .banner-card {
        border: 1px solid rgba(255, 255, 255, 0.08);
        background: linear-gradient(180deg, rgba(13, 20, 33, 0.96), rgba(8, 13, 24, 0.98));
        box-shadow: 0 18px 42px rgba(0, 0, 0, 0.28);
    }

    .banner-card:hover {
        border-color: rgba(255, 255, 255, 0.14);
        box-shadow: 0 22px 48px rgba(0, 0, 0, 0.34);
    }

    .banner-card .banner-media {
        background: radial-gradient(circle at top, rgba(96, 165, 250, 0.18), transparent 55%), #0b1320;
    }

    .banner-card .banner-meta {
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        background: linear-gradient(180deg, transparent, rgba(255, 255, 255, 0.02));
    }

    .banner-empty-state {
        position: relative;
        overflow: hidden;
    }

    .banner-empty-state::before {
        content: '';
        position: absolute;
        inset: auto 0 0 0;
        height: 5px;
        background: linear-gradient(90deg, transparent, rgba(245, 158, 11, 0.55), transparent);
    }

    .banner-empty-state .empty-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 3.5rem;
        height: 3.5rem;
        margin: 0 auto;
        border-radius: 999px;
        background: rgba(245, 158, 11, 0.08);
        border: 1px solid rgba(245, 158, 11, 0.16);
        box-shadow: 0 0 0 6px rgba(245, 158, 11, 0.04);
    }

    /* ── Cyber Badge Overlays ────────────────────────────────── */
    .floating-badge {
        backdrop-filter: blur(8px);
        font-weight: 900 !important;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.18);
    }

    .pill-active { background: rgba(16, 185, 129, 0.18); border: 1px solid rgba(16, 185, 129, 0.35); color: #6ee7b7; }
    .pill-suspended { background: rgba(239, 68, 68, 0.18); border: 1px solid rgba(239, 68, 68, 0.35); color: #fca5a5; }
    .pill-position { background: rgba(245, 158, 11, 0.18); border: 1px solid rgba(245, 158, 11, 0.35); color: #fcd34d; }

    .panel-card-glass:hover {
        transform: translateY(-2px);
        border-color: rgba(255, 255, 255, 0.12);
    }

    .panel-card-glass article,
    .panel-card-glass > article {
        transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .panel-card-glass article:hover,
    .panel-card-glass > article:hover {
        border-color: rgba(255, 255, 255, 0.12);
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.25);
    }

    .banner-empty {
        background: linear-gradient(145deg, rgba(13, 20, 33, 0.94), rgba(8, 13, 24, 0.94));
        border: 1px dashed rgba(255, 255, 255, 0.10);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen py-10 relative overflow-hidden dashboard-transparent">
    {{-- Ambient Neon Glow Base --}}
    <div class="absolute top-0 right-1/4 w-96 h-96 bg-brand-500/5 rounded-full blur-[150px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-1/4 w-96 h-96 bg-amber-500/5 rounded-full blur-[150px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 space-y-6 relative z-10">

        {{-- ── HEADER CONTROL ───────────────────────────────────── --}}
        <div class="flex flex-col gap-4 border-b border-white/5 pb-5">
            <div class="mb-2">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/5 bg-white/5 hover:bg-white/10 px-4 py-2 text-xs font-bold text-slate-300 transition-all uppercase tracking-widest w-fit">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Dasbor
                </a>
            </div>
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                    <span class="text-xs font-bold uppercase tracking-widest text-amber-500/80">Marketplace Campaign Engine</span>
                </div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">Kelola Banner Iklan</h1>
                <p class="text-slate-400 text-sm mt-0.5">Atur baliho promo, event top-up, dan spanduk penawaran utama di halaman depan web.</p>
            </div>
        </div>

        {{-- ── ALERTS & VALIDATION OVERVIEW ──────────────────────── --}}
        @if(session('success'))
            <div class="flex items-start gap-3 rounded-2xl border border-emerald-500/30 bg-emerald-500/5 p-4 backdrop-blur-md">
                <svg class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-medium text-emerald-300">{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-2xl border border-rose-500/30 bg-rose-500/5 p-4 backdrop-blur-md">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-rose-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" w-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-bold text-rose-300">Gagal memproses berkas banner:</h3>
                        <ul class="mt-1.5 space-y-1 text-xs text-rose-400/90 font-medium">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- ── FORM CONTROL: GENERATE NEW BANNER ─────────────────── --}}
          <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" 
              class="panel-card-glass banner-form-shell rounded-3xl p-6 grid gap-5 lg:grid-cols-2">
            @csrf
            
            <div class="lg:col-span-2 border-b border-white/5 pb-2">
                <h2 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                    <span class="banner-header-chip"><span>➕</span><span>Banner Baru</span></span>
                    <span class="text-slate-500 font-medium normal-case tracking-normal">Daftarkan material promosi untuk halaman depan</span>
                </h2>
            </div>

            {{-- Title --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Judul Banner <span class="text-amber-500">*</span></label>
                  <input name="title" type="text" 
                      class="w-full rounded-xl input-glass px-4 py-3 text-sm text-white placeholder:text-slate-600 outline-none @error('title') border-red-500/40 bg-red-500/5 @enderror" 
                       placeholder="Contoh: Mega Flash Sale Ramadhan" value="{{ old('title') }}" required>
                @error('title')<p class="mt-1 text-[11px] text-red-400 font-medium">{{ $message }}</p>@enderror
            </div>

            {{-- Subtitle --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Subjudul / Deskripsi Pendek</label>
                  <input name="subtitle" type="text" 
                      class="w-full rounded-xl input-glass px-4 py-3 text-sm text-white placeholder:text-slate-600 outline-none" 
                       placeholder="Contoh: Diskon Top-up s.d 80% All Games" value="{{ old('subtitle') }}">
                @error('subtitle')<p class="mt-1 text-[11px] text-red-400 font-medium">{{ $message }}</p>@enderror
            </div>

            {{-- File Image Upload --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Unggah File Gambar</label>
                  <input name="image" type="file" accept="image/*" 
                      class="w-full rounded-xl input-glass px-3 py-2 text-xs text-slate-400 outline-none file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:surface-weak file:text-white file:hover:surface-weak file:transition-colors @error('image') border-red-500/40 bg-red-500/5 @enderror">
                <p class="mt-1.5 text-[10px] text-slate-500 leading-relaxed">Format: JPG, PNG, WebP. Maks 5MB.<br>Rekomendasi rasio -> Hero: 4:5 portrait | Featured: 3:1 landscape.</p>
                @error('image')<p class="mt-1 text-[11px] text-red-400 font-medium">{{ $message }}</p>@enderror
            </div>

            {{-- Image URL Alternative --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Atau Pasang URL Gambar Eksternal</label>
                  <input name="image_url" type="url" 
                      class="w-full rounded-xl input-glass px-4 py-3 text-sm text-white placeholder:text-slate-600 outline-none @error('image_url') border-red-500/40 bg-red-500/5 @enderror" 
                       placeholder="https://domain-hosting-gambar.com/foto.webp" value="{{ old('image_url') }}">
                <p class="mt-1.5 text-[10px] text-slate-500 leading-relaxed">Gunakan opsi ini jika aset gambar di-host di luar server aplikasi (Bypass Upload).</p>
                @error('image_url')<p class="mt-1 text-[11px] text-red-400 font-medium">{{ $message }}</p>@enderror
            </div>

            {{-- Destination Link --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">URL Link Tujuan (Redirect Target)</label>
                  <input name="link_url" type="url" 
                      class="w-full rounded-xl input-glass px-4 py-3 text-sm text-white placeholder:text-slate-600 outline-none @error('link_url') border-red-500/40 bg-red-500/5 @enderror" 
                       placeholder="https://lapakgaming.neoverse.my.id/marketplace/trending" value="{{ old('link_url') }}">
                @error('link_url')<p class="mt-1 text-[11px] text-red-400 font-medium">{{ $message }}</p>@enderror
            </div>

            {{-- Position Selection --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Posisi Layout Banner</label>
                <select name="position" class="w-full rounded-xl input-glass px-4 py-3 text-sm text-white outline-none @error('position') border-red-500/40 bg-red-500/5 @enderror">
                    <option value="hero" @selected(old('position') === 'hero')>Slotted: Hero Banner (Halaman Utama Atas)</option>
                    <option value="featured" @selected(old('position') === 'featured')>Slotted: Featured Promo (Tengah Beranda)</option>
                </select>
                @error('position')<p class="mt-1 text-[11px] text-red-400 font-medium">{{ $message }}</p>@enderror
            </div>

            {{-- Option Active Switch & Submission --}}
            <div class="lg:col-span-2 border-t border-white/5 pt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <label class="inline-flex items-center gap-3 cursor-pointer group w-max">
                    <input type="checkbox" name="is_active" value="1" class="w-4 h-4 rounded border-white/10 bg-black/40 text-amber-500 focus:ring-0 focus:ring-offset-0" @if(old('is_active', true)) checked @endif>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 group-hover:text-white transition-colors">Setel Status Aktif Langsung</span>
                </label>
                
                <button type="submit" 
                        class="w-full sm:w-auto rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 px-6 py-3 text-xs font-bold text-slate-950 transition-all shadow-md shadow-amber-500/10 hover:scale-[1.01]">
                    PUBLIKASIKAN BANNER
                </button>
            </div>
        </form>

        {{-- ── DECK GRID: ACTIVE BANNER LISTS ─────────────────────── --}}
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($banners as $banner)
                <article class="panel-card-glass rounded-3xl overflow-hidden flex flex-col group hover:scale-[1.01] hover:border-white/10 transition-all duration-300">
                    
                    {{-- Media Frame Component --}}
                    <div class="relative h-44 w-full bg-black/40 overflow-hidden shrink-0 border-b border-white/5">
                        
                        {{-- 🛠️ PENERAPAN KODE EMAS LU: Pakai murni $banner->image_url tanpa diotak-atik --}}
                        <img src="{{ $banner->image_url }}" 
                             alt="{{ $banner->title }}" 
                             class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500"
                             onerror="this.onerror=null; this.src='https://placehold.co/600x400/0a111e/94a3b8?text=Image+Missing';">
                        
                        {{-- Absolute Overlays Badges --}}
                        <div class="absolute top-3 left-3">
                            <span class="floating-badge text-[10px] font-extrabold px-2.5 py-1 rounded-md pill-position">
                                📍 {{ $banner->position }}
                            </span>
                        </div>
                        <div class="absolute top-3 right-3">
                            <span class="floating-badge text-[10px] font-extrabold px-2.5 py-1 rounded-md {{ $banner->is_active ? 'pill-active' : 'pill-suspended' }}">
                                {{ $banner->is_active ? '⚡ Aktif' : '🔒 Off' }}
                            </span>
                        </div>
                    </div>

                    {{-- Campaign Meta Content --}}
                    <div class="banner-meta p-5 flex-1 flex flex-col justify-between space-y-4">
                        <div class="space-y-1 min-w-0">
                            <h2 class="text-base font-extrabold text-white tracking-tight truncate" title="{{ $banner->title }}">
                                {{ $banner->title }}
                            </h2>
                            <p class="text-xs text-slate-400 leading-relaxed line-clamp-2 min-h-[32px]">
                                {{ $banner->subtitle ?? 'Tidak ada deskripsi subjudul.' }}
                            </p>
                        </div>

                        {{-- Action Removal Block --}}
                                                                                <div class="flex gap-2">
                                                                                    <a href="{{ route('admin.banners.edit', $banner) }}" class="banner-secondary-btn w-1/2 inline-flex items-center justify-center gap-1.5 rounded-xl border border-white/6 bg-white/5 px-3 py-2.5 text-xs font-bold text-white hover:bg-white/10 transition-all">
                                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536M9 11l6-6 3 3-6 6H9v-3z"/></svg>
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="w-1/2"
                                                                onsubmit="return confirm('Hapus baliho kampanye \'{{ addslashes($banner->title) }}\'?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                            class="banner-danger-btn w-full inline-flex items-center justify-center gap-1.5 rounded-xl border border-rose-500/30 bg-rose-500/10 px-3 py-2.5 text-xs font-bold text-rose-400 hover:bg-rose-600 hover:text-white transition-all">
                                                                    <svg class="w-3.5 h-3.5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                                    </svg>
                                                                    COPOT BANNER IKLAN
                                                            </button>
                                                    </form>
                                                </div>
                    </div>

                </article>
            @empty
                <div class="md:col-span-2 lg:col-span-3 rounded-3xl panel-card-glass banner-empty banner-empty-state py-16 text-center text-slate-500">
                    <div class="empty-badge text-2xl mb-3">🖼️</div>
                    <p class="font-bold text-slate-400">Belum ada material promosi aktif.</p>
                    <p class="text-xs text-slate-600 mt-1">Gunakan panel formulir di atas untuk mempublikasikan banner pertamamu lek.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection
