@extends('layouts.app')

@section('title', 'Kelola Banner — Admin')

@push('styles')
<style>
    /* ── True Glassmorphism Control Panel ─────────────────────── */
    .dashboard-transparent {
        background: transparent !important;
    }
    
    .panel-card-glass {
        background: rgba(10, 17, 30, 0.35) !important; /* Transparansi murni 35% */
        backdrop-filter: blur(24px) saturate(160%);
        border: 1px solid rgba(255, 255, 255, 0.06);
        box-shadow: 0 12px 40px 0 rgba(0, 0, 0, 0.5);
    }

    .input-glass {
        background: rgba(5, 9, 16, 0.45) !important;
        border: 1px solid rgba(255, 255, 255, 0.08);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .input-glass:focus {
        border-color: rgba(245, 158, 11, 0.5) !important;
        box-shadow: 0 0 14px rgba(245, 158, 11, 0.15);
    }
    .input-glass option {
        background: #0d1421;
        color: #e2e8f0;
    }

    /* ── Cyber Badge Overlays ────────────────────────────────── */
    .floating-badge {
        backdrop-filter: blur(8px);
        font-[900] !important;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .pill-active { background: rgba(16, 185, 129, 0.2); border: 1px solid rgba(16, 185, 129, 0.4); color: #34d399; }
    .pill-suspended { background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); color: #f87171; }
    .pill-position { background: rgba(245, 158, 11, 0.2); border: 1px solid rgba(245, 158, 11, 0.4); color: #fbbf24; }
</style>
@endpush

@section('content')
<div class="min-h-screen py-10 relative overflow-hidden dashboard-transparent">
    {{-- Ambient Neon Glow Base --}}
    <div class="absolute top-0 right-1/4 w-96 h-96 bg-brand-500/5 rounded-full blur-[150px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-1/4 w-96 h-96 bg-amber-500/5 rounded-full blur-[150px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 space-y-6 relative z-10">

        {{-- ── HEADER CONTROL ───────────────────────────────────── --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-white/5 pb-5">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                    <span class="text-xs font-bold uppercase tracking-widest text-amber-500/80">Marketplace Campaign Engine</span>
                </div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">Kelola Banner Iklan</h1>
                <p class="text-slate-400 text-sm mt-0.5">Atur baliho promo, event top-up, dan spanduk penawaran utama di halaman depan web.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}"
               class="inline-flex items-center gap-2 rounded-xl surface-weak hover:surface-weak border border-white/5 px-4 py-2.5 text-xs font-bold text-slate-300 transition-all tracking-wide self-start sm:self-auto">
                Dashboard
            </a>
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
              class="panel-card-glass rounded-3xl p-6 grid gap-5 lg:grid-cols-2">
            @csrf
            
            <div class="lg:col-span-2 border-b border-white/5 pb-2">
                <h2 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                    <span>➕</span> Daftarkan Material Promosi Baru
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
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
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
                                                    <a href="{{ route('admin.banners.edit', $banner) }}" class="w-1/2 inline-flex items-center justify-center gap-1.5 rounded-xl border border-white/6 bg-white/5 px-3 py-2.5 text-xs font-bold text-white hover:bg-white/10 transition-all">
                                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536M9 11l6-6 3 3-6 6H9v-3z"/></svg>
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="w-1/2"
                                                                onsubmit="return confirm('Hapus baliho kampanye \'{{ addslashes($banner->title) }}\'?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                            class="w-full inline-flex items-center justify-center gap-1.5 rounded-xl border border-rose-500/30 bg-rose-500/10 px-3 py-2.5 text-xs font-bold text-rose-400 hover:bg-rose-600 hover:text-white transition-all">
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
                <div class="md:col-span-2 lg:col-span-3 rounded-3xl panel-card-glass py-16 text-center text-slate-500">
                    <div class="text-4xl mb-3">🖼️</div>
                    <p class="font-bold text-slate-400">Belum ada material promosi aktif.</p>
                    <p class="text-xs text-slate-600 mt-1">Gunakan panel formulir di atas untuk mempublikasikan banner pertamamu lek.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection
