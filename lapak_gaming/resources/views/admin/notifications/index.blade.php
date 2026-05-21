@extends('layouts.app')

@section('title', 'Pesan & Notifikasi Admin — Admin')

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

    /* ── Cyber Status Pills & Badges ─────────────────────────── */
    .pill-notify {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: .6875rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }
    .pill-general { background: rgba(59, 130, 246, 0.15); border: 1px solid rgba(59, 130, 246, 0.3); color: #60a5fa; }
    .pill-reward  { background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.3); color: #fbbf24; }
    
    .target-badge {
        font-size: .6875rem;
        font-weight: 700;
        color: #94a3b8;
        background: rgba(255, 255, 255, 0.05);
        padding: 2px 8px;
        border-radius: 4px;
        border: 1px solid rgba(255, 255, 255, 0.03);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen py-10 relative overflow-hidden dashboard-transparent">
    {{-- Ambient Neon Glow Base --}}
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-brand-500/5 rounded-full blur-[150px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-blue-500/5 rounded-full blur-[150px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 space-y-6 relative z-10">

        {{-- ── HEADER CONTROL ───────────────────────────────────── --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-white/5 pb-5">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                    <span class="text-xs font-bold uppercase tracking-widest text-blue-400/90">Global Broadcast HQ</span>
                </div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">Pesan & Notifikasi</h1>
                <p class="text-slate-400 text-sm mt-0.5">Kirimkan maklumat pengumuman, promosi event, atau hadiah ke target audiens sistem.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/5 px-4 py-2.5 text-xs font-bold text-slate-300 transition-all tracking-wide self-start sm:self-auto">
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

        {{-- ── FORM CONTROL: BROADCAST GENERATOR ─────────────────── --}}
        <form action="{{ route('admin.notifications.send') }}" method="POST" 
              class="panel-card-glass rounded-3xl p-6 grid gap-5 lg:grid-cols-2">
            @csrf

            <div class="lg:col-span-2 border-b border-white/5 pb-2">
                <h2 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                    <span>📢</span> Buat Siaran Notifikasi Baru
                </h2>
            </div>

            {{-- Target Audience Selector --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Target Penerima (Audience)</label>
                <select name="audience" class="w-full rounded-xl input-glass px-4 py-3 text-sm text-white outline-none">
                    <option value="all">👥 Semua Akun Terdaftar</option>
                    <option value="buyer">🕹️ Khusus Pihak Buyer</option>
                    <option value="seller">🏪 Khusus Pihak Seller</option>
                </select>
            </div>

            {{-- Category Selector --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Kategori Klasifikasi Pesan</label>
                <select name="category" class="w-full rounded-xl input-glass px-4 py-3 text-sm text-white outline-none" required>
                    <option value="general">🔵 Pengumuman Umum / Maintenance</option>
                    <option value="event_reward">🟡 Event Spesial & Klaim Hadiah</option>
                </select>
            </div>

            {{-- Title Input --}}
            <div class="lg:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Judul Notifikasi / Subjek <span class="text-amber-500">*</span></label>
                <input name="title" type="text" required
                       class="w-full rounded-xl input-glass px-4 py-3 text-sm text-white placeholder:text-slate-600 outline-none" 
                       placeholder="Contoh: Pemeliharaan Sistem Selesai - Fitur Toko Baru Aktif!">
            </div>

            {{-- Body Textarea Input --}}
            <div class="lg:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Isi Pesan Siaran Resmi <span class="text-amber-500">*</span></label>
                <textarea name="body" rows="5" required
                          class="w-full rounded-xl input-glass px-4 py-3 text-sm text-white placeholder:text-slate-600 outline-none resize-none" 
                          placeholder="Tulis rincian pesan notifikasi secara detail di sini agar dipahami oleh user..."></textarea>
            </div>

            {{-- Action Target Redirect Link --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Link Tautan Opsional (Redirect Target URL)</label>
                <input name="link" type="url" 
                       class="w-full rounded-xl input-glass px-4 py-3 text-sm text-white placeholder:text-slate-600 outline-none" 
                       placeholder="https://lapakgaming.neoverse.my.id/event/claim">
                <p class="mt-1 text-[10px] text-slate-500">User akan diarahkan ke link ini jika menekan pesan notifikasi.</p>
            </div>

            {{-- Submission Button Control --}}
            <div class="flex items-end justify-end">
                <button type="submit" 
                        class="w-full sm:w-auto rounded-xl bg-gradient-to-r from-amber-500 to-brand-500 hover:from-amber-400 hover:to-brand-400 px-6 py-3 text-xs font-bold text-slate-950 transition-all shadow-md shadow-brand-500/10 hover:scale-[1.01]">
                    LUNCURKAN NOTIFIKASI
                </button>
            </div>
        </form>

        {{-- ── DECK GRID: HISTORICAL BROADCAST FEED ───────────────── --}}
        <div class="panel-card-glass rounded-3xl p-6">
            <div class="border-b border-white/5 pb-3 mb-5 flex items-center justify-between">
                <h2 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                    <span>⏱️</span> Log Riwayat Siaran Terbaru
                </h2>
                <span class="text-xs font-mono text-slate-500 bg-black/20 px-2 py-0.5 rounded border border-white/5">System Feed</span>
            </div>

            <div class="space-y-4">
                @forelse ($notifications as $notification)
                    <article class="rounded-2xl border border-white/5 bg-black/20 p-4 hover:bg-white/5 transition-all duration-200 group">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-white/5 pb-2.5">
                            <div class="flex flex-wrap items-center gap-2.5">
                                {{-- Icon Category Decorator --}}
                                @php
                                    $cleanCategory = strtolower($notification->metadata['category'] ?? '');
                                    $isReward = ($cleanCategory === 'event_reward' || str_contains($cleanCategory, 'reward'));
                                    $audienceLabel = match ($notification->metadata['audience'] ?? 'all') {
                                        'buyer' => 'Buyer',
                                        'seller' => 'Seller',
                                        default => 'Semua',
                                    };
                                @endphp
                                <span class="pill-notify {{ $isReward ? 'pill-reward' : 'pill-general' }}">
                                    <span class="w-1 h-1 rounded-full bg-current"></span>
                                    {{ $notification->category_label }}
                                </span>
                                <span class="target-badge">Target: {{ $audienceLabel }}</span>
                                <span class="target-badge">Diterima oleh {{ $notification->deliveries_count ?? 0 }} akun</span>

                                <h3 class="font-bold text-white tracking-tight group-hover:text-amber-400 transition-colors">
                                    {{ $notification->title }}
                                </h3>
                            </div>
                            
                            {{-- Timing Log --}}
                            <span class="text-xs text-slate-500 font-medium shrink-0 font-mono sm:text-right">
                                🕒 {{ $notification->created_at->diffForHumans() }}
                            </span>
                        </div>
                        
                        {{-- Broadcast Body Description --}}
                        <p class="mt-3 text-sm text-slate-400 leading-relaxed font-medium break-words">
                            {{ $notification->body }}
                        </p>
                        
                        {{-- Optional Link Indicator --}}
                        @if(!empty($notification->link))
                            <div class="mt-3 pt-2 border-t border-white/5 flex">
                                <a href="{{ $notification->link }}" target="_blank" 
                                   class="text-[11px] font-bold text-blue-400 hover:text-blue-300 inline-flex items-center gap-1 font-mono">
                                    🔗 Link Target: {{ $notification->link }}
                                </a>
                            </div>
                        @endif
                    </article>
                @empty
                    <div class="py-12 text-center text-slate-500">
                        <div class="text-4xl mb-2">📭</div>
                        <p class="text-sm font-bold text-slate-400">Belum ada rekam jejak notifikasi</p>
                        <p class="text-xs text-slate-600 mt-0.5">Semua pesan siaran global yang kamu kirim akan diarsipkan di sini.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection