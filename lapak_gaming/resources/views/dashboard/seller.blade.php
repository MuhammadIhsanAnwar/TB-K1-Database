@extends('layouts.app')

@section('title', 'Seller Dashboard')

@push('styles')
<style>
    /* ── True Glassmorphism Tech Theme ─────────────────────────── */
    .dashboard-transparent {
        background: transparent !important; /* Paksa transparan agar elemen bergerak di belakang terlihat jelas */
    }
    
    .panel-card-glass {
        background: rgba(10, 17, 30, 0.35) !important; /* Di-tipiskan jadi 35% agar tembus pandang */
        backdrop-filter: blur(24px) saturate(160%); /* Efek blur kaca premium */
        border: 1px solid rgba(255, 255, 255, 0.06);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.4);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .panel-card-glass:hover {
        border-color: rgba(37, 99, 235, 0.35);
        background: rgba(13, 22, 38, 0.5) !important;
        transform: translateY(-3px);
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.6), 0 0 25px rgba(37, 99, 235, 0.15);
    }

    /* ── Sub Item List di dalam Kaca ───────────────────────────── */
    .sub-item-glass {
        background: rgba(255, 255, 255, 0.02) !important;
        border: 1px solid rgba(255, 255, 255, 0.04);
        backdrop-filter: blur(8px);
        transition: all 0.2s ease;
    }

    .sub-item-glass:hover {
        background: rgba(255, 255, 255, 0.05) !important;
        border-color: rgba(255, 255, 255, 0.1);
    }

    /* ── Status Order Neon Badges ─────────────────────────────── */
    .status-badge {
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .status-success, .status-completed {
        background: rgba(16, 185, 129, 0.15);
        border: 1px solid rgba(16, 185, 129, 0.4);
        color: #34d399;
    }
    .status-pending, .status-processing {
        background: rgba(245, 158, 11, 0.15);
        border: 1px solid rgba(245, 158, 11, 0.4);
        color: #fbbf24;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen py-10 relative overflow-hidden dashboard-transparent">
    {{-- Ambient Light penambah kontras teks di atas background bergerak --}}
    <div class="absolute top-0 right-1/4 w-96 h-96 bg-brand-500/10 rounded-full blur-[150px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 space-y-8 relative z-10">
        
        {{-- ── WELCOME HEADER ───────────────────────────────────── --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-800/40 pb-6">
            <div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">Pusat Kendali Seller</h1>
                <p class="text-slate-400 text-sm mt-1">Selamat datang kembali, <span class="text-brand-400 font-semibold">{{ $user->name }}</span>. Pantau performa tokomu hari ini.</p>
            </div>
            <div class="mt-4 md:mt-0 text-xs text-slate-400 font-medium px-4 py-2 rounded-xl border border-white/5 bg-black/20 backdrop-blur-md">
                Status Akun: <span class="text-emerald-400 font-bold uppercase tracking-wider">Verified Seller</span>
            </div>
        </div>

        {{-- ── PENDING / REJECTED STATUS WARNING ─────────────────── --}}
        @if($user->seller_status === 'pending')
        <div class="rounded-2xl border border-amber-500/30 bg-amber-500/5 p-5 backdrop-blur-md animate-pulse">
            <div class="flex items-start gap-4">
                <div class="p-2 rounded-xl bg-amber-500/10 text-amber-400 shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-base font-bold text-amber-200">Toko Anda Sedang Dalam Tahap Peninjauan</h3>
                    <p class="text-xs text-slate-400 mt-1 leading-relaxed">Pendaftaran tokomu sedang diperiksa berkasnya oleh admin. Kamu akan menerima notifikasi jika status toko berubah. Selama masa peninjauan, proses upload produk baru akan ditangguhkan sementara.</p>
                </div>
            </div>
        </div>
        @elseif($user->seller_status === 'rejected')
        <div class="rounded-2xl border border-rose-500/30 bg-rose-500/5 p-5 backdrop-blur-md">
            <div class="flex items-start gap-4">
                <div class="p-2 rounded-xl bg-rose-500/10 text-rose-400 shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-base font-bold text-rose-200">Pengajuan Pendaftaran Toko Ditolak</h3>
                    <p class="text-xs text-slate-400 mt-1 leading-relaxed"><span class="text-rose-400/90 font-semibold">Alasan:</span> {{ $user->seller_rejection_reason ?? 'Berkas yang diunggah kurang jelas atau tidak valid.' }}</p>
                    <a href="{{ route('seller.register.form') }}" class="text-xs text-brand-400 hover:text-brand-300 mt-3 inline-flex items-center gap-1 font-semibold transition-colors underline">
                        Perbaiki Berkas & Ajukan Kembali →
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- ── STATS CARDS GRID (TRANSPARENT) ────────────────────── --}}
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            {{-- Card 1: Saldo --}}
            <div class="rounded-2xl p-6 panel-card-glass flex items-center justify-between group">
                <div class="space-y-1">
                    <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Saldo Hasil Penjualan</div>
                    <div class="text-3xl font-extrabold text-amber-400">
                        <span class="text-lg font-bold text-amber-500/70">Rp</span> {{ number_format($seller->balance ?? 0, 0, ',', '.') }}
                    </div>
                </div>
                <div class="w-11 h-11 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-400 group-hover:scale-110 transition-transform">
                    💰
                </div>
            </div>
            
            {{-- Card 2: Produk Aktif --}}
            <div class="rounded-2xl p-6 panel-card-glass flex items-center justify-between group">
                <div class="space-y-1">
                    <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Katalog Produk Aktif</div>
                    <div class="text-3xl font-extrabold text-white tracking-tight">
                        {{ $products->where('status', 'published')->count() }} <span class="text-xs font-medium text-slate-400">Item</span>
                    </div>
                </div>
                <div class="w-11 h-11 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-400 group-hover:scale-110 transition-transform">
                    📦
                </div>
            </div>

            {{-- Card 3: Total Order --}}
            <div class="rounded-2xl p-6 panel-card-glass flex items-center justify-between group">
                <div class="space-y-1">
                    <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Pesanan Masuk</div>
                    <div class="text-3xl font-extrabold text-white tracking-tight">
                        {{ $orders->count() }} <span class="text-xs font-medium text-slate-400">Transaksi</span>
                    </div>
                </div>
                <div class="w-11 h-11 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-400 group-hover:scale-110 transition-transform">
                    📊
                </div>
            </div>
        </div>

        {{-- ── QUICK ACTIONS MACRO NAVIGATION ────────────────────── --}}
        <div class="grid gap-4 sm:grid-cols-3">
            <a href="{{ route('seller.produk.create') }}" 
               class="flex items-center justify-between p-5 bg-gradient-to-r from-amber-500/80 to-orange-500/80 backdrop-blur-md rounded-2xl transition-all hover:scale-[1.01] active:scale-[0.99] shadow-lg shadow-amber-500/5 group text-slate-950">
                <div>
                    <h3 class="text-lg font-bold tracking-tight text-slate-950">Tambah Produk</h3>
                    <p class="text-slate-950/70 text-xs font-medium mt-0.5">Buka etalase jualan baru</p>
                </div>
                <div class="bg-white/20 p-2.5 rounded-xl">
                    <svg class="w-5 h-5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                </div>
            </a>
            
            <a href="{{ route('seller.produk.index') }}" 
               class="flex items-center justify-between p-5 panel-card-glass rounded-2xl group">
                <div>
                    <h3 class="text-lg font-bold text-white tracking-tight">Kelola Etalase</h3>
                    <p class="text-slate-400 text-xs font-medium mt-0.5">Stok harian & arsip barang</p>
                </div>
                <div class="bg-white/5 p-2.5 rounded-xl group-hover:bg-amber-500 group-hover:text-slate-950 transition-colors text-slate-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </div>
            </a>
            
            <a href="{{ route('chat.inbox') }}" 
               class="flex items-center justify-between p-5 panel-card-glass rounded-2xl group">
                <div>
                    <h3 class="text-lg font-bold text-white tracking-tight">Pesan Pelanggan</h3>
                    <p class="text-slate-400 text-xs font-medium mt-0.5">Balas pertanyaan & negosiasi</p>
                </div>
                <div class="bg-white/5 p-2.5 rounded-xl group-hover:bg-brand-500 group-hover:text-white transition-colors text-slate-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a.863.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
            </a>
        </div>

        {{-- ── TWO COLUMNS: ACTIVITIES PREVIEW (TEMBUS PANDANG) ────── --}}
        <div class="grid gap-8 lg:grid-cols-2">
            
            {{-- Left Column: Products Preview --}}
            <section class="rounded-2xl p-6 panel-card-glass space-y-5">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2">
                        <span>📦</span> Katalog Dagangan Terbaru
                    </h2>
                    <a href="{{ route('seller.produk.index') }}" class="text-xs text-brand-400 hover:text-brand-300 font-semibold transition-colors">Lihat Semua →</a>
                </div>
                
                <div class="space-y-3">
                    @forelse ($products->take(5) as $product)
                        <div class="flex items-center justify-between p-3.5 rounded-xl sub-item-glass">
                            <div class="flex items-center gap-3 min-w-0">
                                <img src="{{ $product->image_url }}" class="w-9 h-9 rounded-lg object-cover bg-black/40 border border-white/5" alt="Thumbnail {{ $product->name }}">
                                <div class="min-w-0">
                                    <div class="font-semibold text-sm text-white truncate">{{ $product->name }}</div>
                                    <div class="text-[10px] text-slate-400 font-medium mt-0.5 uppercase tracking-wider flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $product->status === 'published' ? 'bg-emerald-400' : 'bg-rose-500' }}"></span>
                                        {{ $product->status }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-sm font-bold text-amber-400 whitespace-nowrap pl-2">
                                <span class="text-[10px] text-amber-500/70 font-medium">Rp</span> {{ number_format($product->price, 0, ',', '.') }}
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center rounded-xl border border-dashed border-slate-800/60">
                            <p class="text-xs text-slate-500 italic">Kamu belum memiliki produk jualan di toko ini.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- Right Column: Orders Preview --}}
            <section class="rounded-2xl p-6 panel-card-glass space-y-5">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2">
                        <span>📥</span> Antrean Pesanan Masuk
                    </h2>
                    <span class="text-[11px] font-bold text-slate-400 bg-black/30 px-2.5 py-1 rounded-md border border-white/5">5 Terbaru</span>
                </div>
                
                <div class="space-y-3">
                    @forelse ($orders->take(5) as $item)
                        <div class="p-3.5 rounded-xl sub-item-glass flex items-center justify-between gap-4">
                            <div class="min-w-0">
                                <div class="text-[10px] font-bold text-brand-400 uppercase tracking-wider">
                                    #{{ $item->order?->invoice_number ?? $item->order?->order_code ?? 'INV-UNKNWN' }}
                                </div>
                                <div class="text-sm font-semibold text-white truncate mt-0.5">
                                    {{ $item->product?->name ?? 'Produk Telah Dihapus' }}
                                </div>
                            </div>
                            
                            @php
                                $statusClass = 'status-pending';
                                $orderStatus = strtolower($item->order?->status ?? 'pending');
                                if(in_array($orderStatus, ['completed', 'success', 'berhasil'])) {
                                    $statusClass = 'status-completed';
                                }
                            @endphp
                            <span class="px-2.5 py-1 text-[10px] font-bold rounded-md uppercase tracking-wider {{ $statusClass }} shrink-0">
                                {{ $item->order?->status ?? 'PENDING' }}
                            </span>
                        </div>
                    @empty
                        <div class="py-10 text-center rounded-xl border border-dashed border-slate-800/60">
                            <p class="text-xs text-slate-500 italic">Belum ada pesanan masuk dari pembeli.</p>
                        </div>
                    @endforelse
                </div>
            </section>
            
        </div>
    </div>
</div>
@endsection