@extends('layouts.app')

@section('title', 'Seller Dashboard')

@push('styles')
<style>
    .reveal {
        opacity: 0;
        transform: translateY(30px);
        animation: revealUp .8s ease forwards;
    }

    .reveal-delay-1 {
        animation-delay: .15s;
    }

    .reveal-delay-2 {
        animation-delay: .3s;
    }

    .reveal-delay-3 {
        animation-delay: .45s;
    }

    @keyframes revealUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('content')
<div class="mx-auto mt-10 max-w-7xl space-y-7 px-4 md:px-6">
    {{-- Ambient Light penambah kontras teks di atas background bergerak --}}
    <div class="absolute top-0 right-1/4 w-96 h-96 bg-brand-500/10 rounded-full blur-[150px] pointer-events-none"></div>

    <div class="dashboard-wrapper space-y-8 relative z-10">
        
        <div
    class="reveal relative overflow-hidden rounded-[30px] border border-blue-500/20 bg-gradient-to-br from-[#060816] via-[#091225] to-[#0B1730] px-7 py-8 shadow-[0_0_80px_rgba(37,99,235,0.12)]">

    <div
        class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(37,99,235,0.18),transparent_35%)]">
    </div>

    <div class="relative z-10 flex flex-col gap-7 lg:flex-row lg:items-center lg:justify-between">

        <div class="max-w-2xl">
            <div
                class="inline-flex items-center gap-2 rounded-full border border-blue-500/30 bg-blue-500/10 px-4 py-2 text-[11px] font-bold tracking-[0.2em] text-blue-300 backdrop-blur-xl">

                <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>

                SELLER DASHBOARD
            </div>

            <h1
                class="mt-5 text-3xl font-black leading-tight text-white md:text-5xl">
                Pusat Kendali Seller
            </h1>

            <p class="mt-4 max-w-xl text-sm leading-relaxed text-slate-300 md:text-[15px]">
                Kelola toko, pantau pesanan, atur produk, dan lihat performa penjualan tokomu secara real-time.
            </p>
        </div>

        <div
            class="hidden lg:flex h-[180px] w-[180px] items-center justify-center rounded-full border border-blue-500/20 bg-blue-500/5 backdrop-blur-2xl">

            <div
                class="absolute h-[220px] w-[220px] rounded-full bg-blue-500/10 blur-3xl">
            </div>

            <div class="relative z-10 text-7xl">
                🛒
            </div>
        </div>

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
        <div class="reveal reveal-delay-1 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            {{-- Card 1: Saldo --}}
            <div class="rounded-2xl p-6 group relative overflow-hidden rounded-[26px] border border-blue-500/20 bg-[#0B1220]/95 p-5 transition duration-300 hover:-translate-y-1.5 hover:border-blue-400/40 hover:shadow-[0_0_30px_rgba(59,130,246,0.12)] flex items-center justify-between group reveal">
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
            <div class="rounded-2xl p-6 group relative overflow-hidden rounded-[26px] border border-blue-500/20 bg-[#0B1220]/95 p-5 transition duration-300 hover:-translate-y-1.5 hover:border-blue-400/40 hover:shadow-[0_0_30px_rgba(59,130,246,0.12)] flex items-center justify-between group">
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
            <div class="rounded-2xl p-6 group relative overflow-hidden rounded-[26px] border border-blue-500/20 bg-[#0B1220]/95 p-5 transition duration-300 hover:-translate-y-1.5 hover:border-blue-400/40 hover:shadow-[0_0_30px_rgba(59,130,246,0.12)] flex items-center justify-between group">
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
               class="flex items-center justify-between p-5 group relative overflow-hidden rounded-[26px] border border-blue-500/20 bg-[#0B1220]/95 p-5 transition duration-300 hover:-translate-y-1.5 hover:border-blue-400/40 hover:shadow-[0_0_30px_rgba(59,130,246,0.12)] rounded-2xl group">
                <div>
                    <h3 class="text-lg font-bold text-white tracking-tight">Tambah Produk</h3>
                    <p class="text-slate-400 text-xs font-medium mt-0.5">Buka etalase jualan baru</p>
                </div>
                <div class="bg-white/20 p-2.5 rounded-xl">
                    <svg class="w-5 h-5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                </div>
            </a>
            
            <a href="{{ route('seller.produk.index') }}" 
               class="flex items-center justify-between p-5 group relative overflow-hidden rounded-[26px] border border-blue-500/20 bg-[#0B1220]/95 p-5 transition duration-300 hover:-translate-y-1.5 hover:border-blue-400/40 hover:shadow-[0_0_30px_rgba(59,130,246,0.12)] rounded-2xl group">
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
               class="flex items-center justify-between p-5 group relative overflow-hidden rounded-[26px] border border-blue-500/20 bg-[#0B1220]/95 p-5 transition duration-300 hover:-translate-y-1.5 hover:border-blue-400/40 hover:shadow-[0_0_30px_rgba(59,130,246,0.12)] rounded-2xl group">
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
            <section class="rounded-2xl p-6 group relative overflow-hidden rounded-[26px] border border-blue-500/20 bg-[#0B1220]/95 p-5 transition duration-300 hover:-translate-y-1.5 hover:border-blue-400/40 hover:shadow-[0_0_30px_rgba(59,130,246,0.12)] space-y-5">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2">
                        <span>📦</span> Katalog Dagangan Terbaru
                    </h2>
                    <a href="{{ route('seller.produk.index') }}" class="text-xs text-brand-400 hover:text-brand-300 font-semibold transition-colors">Lihat Semua →</a>
                </div>
                
                <div class="space-y-3">
                    @forelse ($products->take(5) as $product)
                        <div class="flex items-center justify-between p-3.5 rounded-xl rounded-2xl border border-white/5 bg-white/[0.03] p-4 transition duration-300 hover:border-blue-500/30 hover:bg-blue-500/[0.04] hover:-translate-y-1">
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
            <section class="rounded-2xl p-6 group relative overflow-hidden rounded-[26px] border border-blue-500/20 bg-[#0B1220]/95 p-5 transition duration-300 hover:-translate-y-1.5 hover:border-blue-400/40 hover:shadow-[0_0_30px_rgba(59,130,246,0.12)] space-y-5">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2">
                        <span>📥</span> Antrean Pesanan Masuk
                    </h2>
                    <span class="text-[11px] font-bold text-slate-400 bg-black/30 px-2.5 py-1 rounded-md border border-white/5">5 Terbaru</span>
                </div>
                
                <div class="space-y-3">
                    @forelse ($orders->take(5) as $item)
                        <div class="p-3.5 rounded-xl rounded-2xl border border-white/5 bg-white/[0.03] p-4 transition duration-300 hover:border-blue-500/30 hover:bg-blue-500/[0.04] hover:-translate-y-1 flex items-center justify-between gap-4">
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

@push('scripts')
<script>

const reveals = document.querySelectorAll('.reveal');

function revealOnScroll() {
    reveals.forEach((element) => {
        const windowHeight = window.innerHeight;
        const revealTop = element.getBoundingClientRect().top;
        const revealPoint = 80;

        if (revealTop < windowHeight - revealPoint) {
            element.classList.add('active');
        }
    });
}

window.addEventListener('scroll', revealOnScroll);
window.addEventListener('load', revealOnScroll);

</script>
@endpush

@endsection