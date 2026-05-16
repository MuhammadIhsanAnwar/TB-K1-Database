@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str; 
@endphp

@extends('layouts.app')

@section('title', 'Kelola Produk')

@push('styles')
    <style>
        /* ── Cyber Gaming Theme Glow ───────────────────────────────── */
        .dashboard-glow {
            background: radial-gradient(circle at 50% -20%, rgba(245, 158, 11, 0.15), transparent 60%);
        }

        .premium-card {
            background: rgba(13, 20, 33, 0.7);
            backdrop-filter: blur(16px);
            border: 1px solid #1e2d45;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .premium-card:hover {
            border-color: rgba(245, 158, 11, 0.4);
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.6), 0 0 20px rgba(245, 158, 11, 0.05);
        }

        /* ── Tab Glossy Effect ─────────────────────────────────────── */
        .glossy-tabs {
            background: #090e17;
            border: 1px solid #1e2d45;
        }

        /* ── Custom Status Badges ─────────────────────────────────── */
        .status-published {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #34d399;
            text-shadow: 0 0 10px rgba(16, 185, 129, 0.2);
        }

        .status-archived {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
            text-shadow: 0 0 10px rgba(239, 68, 68, 0.2);
        }

        .type-badge {
            background: rgba(37, 99, 235, 0.15);
            border: 1px solid rgba(37, 99, 235, 0.3);
            color: #60a5fa;
        }
    </style>
@endpush

@section('content')
    <div class="min-h-screen bg-[#060a12] py-12 relative overflow-hidden dashboard-glow">
        {{-- Background Ambient --}}
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-600/5 rounded-full blur-[120px] pointer-events-none"></div>

        <div class="mx-auto max-w-6xl space-y-8 relative z-10 px-4">

            {{-- ── HEADER SECTION ───────────────────────────────────── --}}
            <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                        <span class="text-xs font-bold uppercase tracking-widest text-amber-500/80">Seller Control
                            Panel</span>
                    </div>
                    <h1 class="text-4xl font-extrabold text-white tracking-tight">Kelola Lapak Jualan</h1>
                    <p class="mt-1.5 text-slate-400 text-sm font-medium">Pantau, tambah, dan atur komoditas gaming kamu
                        dengan instan.</p>
                </div>

                <a href="{{ route('seller.produk.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-amber-500 to-orange-500 px-6 py-4 font-bold text-slate-950 hover:from-amber-400 hover:to-orange-400 transition-all shadow-lg shadow-amber-500/10 hover:scale-[1.02]">
                    <svg class="w-5 h-5 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    TAMBAH PRODUK BARU
                </a>
            </div>

            {{-- ── NAVIGATION TABS ──────────────────────────────────── --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-800/60 pb-4">
                <div class="inline-flex rounded-xl p-1 glossy-tabs">
                    <a href="{{ route('seller.produk.index', ['status' => 'active']) }}"
                        class="rounded-lg库 px-5 py-2 text-xs font-bold uppercase tracking-wider transition-all {{ $status === 'active' ? 'bg-amber-500 text-slate-950 shadow-md' : 'text-slate-400 hover:text-white' }}">
                        📦 Produk Aktif ({{ $status === 'active' ? $products->total() : 'On' }})
                    </a>
                    <a href="{{ route('seller.produk.index', ['status' => 'archived']) }}"
                        class="rounded-lg px-5 py-2 text-xs font-bold uppercase tracking-wider transition-all {{ $status === 'archived' ? 'bg-amber-500 text-slate-950 shadow-md' : 'text-slate-400 hover:text-white' }}">
                        📁 Produk Arsip ({{ $status === 'archived' ? $products->total() : 'Off' }})
                    </a>
                </div>

                <div class="text-xs text-slate-500 font-medium">
                    Menampilkan {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} dari
                    {{ $products->total() }} Item
                </div>
            </div>

            {{-- ── PRODUCT LIST GRID ────────────────────────────────── --}}
            <div class="grid gap-4">
                @forelse($products as $product)
                    <div class="group rounded-3xl p-5 md:p-6 premium-card">
                        <div class="flex flex-col gap-5 md:flex-row md:items-center">

                            {{-- Product Thumbnail --}}
                            <div class="relative shrink-0 mx-auto md:mx-0">
                                <img src="{{ Str::startsWith($product->file_path, 'http') ? $product->file_path : Storage::url($product->file_path) }}"
                                    alt="Gambar {{ $product->name }}"
                                    class="w-20 h-20 md:w-24 md:h-24 rounded-2xl object-cover border border-slate-800 group-hover:border-amber-500/40 transition-colors shadow-inner">
                            </div>

                            {{-- Meta Details --}}
                            <div class="flex-1 text-center md:text-left min-w-0">
                                <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-3">
                                    <h2 class="text-xl font-bold text-white tracking-tight truncate">{{ $product->name }}</h2>
                                    <div class="flex items-center justify-center gap-2 mt-1 md:mt-0">
                                        <span
                                            class="px-2.5 py-0.5 text-[10px] font-bold rounded-md uppercase tracking-wider type-badge">
                                            {{ $product->type }}
                                        </span>
                                        <span
                                            class="px-2.5 py-0.5 text-[10px] font-bold rounded-md uppercase tracking-wider {{ $product->status == 'published' ? 'status-published' : 'status-archived' }}">
                                            {{ $product->status == 'published' ? '⚡ Active' : '💤 Drafted' }}
                                        </span>
                                    </div>
                                </div>

                                <p
                                    class="mt-2 text-sm text-slate-400 font-medium flex items-center justify-center md:justify-start gap-2">
                                    <span class="text-slate-500">Kategori:</span>
                                    <span
                                        class="text-slate-300 font-semibold">{{ $product->category?->name ?? 'Tanpa Kategori' }}</span>
                                </p>
                            </div>

                            {{-- Pricing Element --}}
                            <div class="text-center md:text-right border-y md:border-none border-slate-800/50 py-3 md:py-0">
                                <div class="text-[11px] text-slate-500 font-bold uppercase tracking-wider mb-0.5">Harga Produk
                                </div>
                                <div class="text-2xl font-extrabold text-amber-400">
                                    <span class="text-sm font-bold text-amber-500/70">Rp</span>
                                    {{ number_format($product->price, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        {{-- ── CARD ACTIONS FOOTER ──────────────────────────── --}}
                        <div
                            class="mt-5 flex flex-col sm:flex-row justify-end items-center gap-2 border-t border-slate-800/60 pt-4">
                            <a href="{{ route('seller.produk.edit', $product) }}"
                                class="w-full sm:w-auto text-center rounded-xl bg-slate-800 hover:bg-slate-700 px-5 py-2.5 text-xs font-bold text-slate-200 transition-colors tracking-wide">
                                EDIT DATA ITEM
                            </a>

                            @if($status === 'active')
                                <form action="{{ route('seller.produk.destroy', $product) }}" method="POST"
                                    class="w-full sm:w-auto">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="w-full sm:w-auto rounded-xl bg-rose-500/10 border border-rose-500/20 px-5 py-2.5 text-xs font-bold text-rose-400 hover:bg-rose-600 hover:text-white transition-all tracking-wide">
                                        ARSIPKAN LAPAK
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('seller.produk.activate', $product) }}" method="POST"
                                    class="w-full sm:w-auto">
                                    @csrf
                                    <button type="submit"
                                        class="w-full sm:w-auto rounded-xl bg-emerald-500/10 border border-emerald-500/20 px-5 py-2.5 text-xs font-bold text-emerald-400 hover:bg-emerald-500 hover:text-slate-950 transition-all tracking-wide">
                                        AKTIFKAN KEMBALI
                                    </button>
                                </form>

                                <form action="{{ route('seller.produk.forceDestroy', $product) }}" method="POST"
                                    class="w-full sm:w-auto"
                                    onsubmit="return confirm('Hapus produk ini permanen? Tindakan ini tidak bisa dibatalkan.');">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="w-full sm:w-auto rounded-xl bg-rose-600/20 border border-rose-500/30 px-5 py-2.5 text-xs font-bold text-rose-500 hover:bg-rose-600 hover:text-white transition-all tracking-wide">
                                        HAPUS PERMANEN
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    {{-- ── EMPTY STATE DESIGN ──────────────────────────────── --}}
                    <div class="rounded-3xl border-2 border-dashed border-slate-800 p-16 text-center bg-slate-900/20">
                        <div class="text-4xl mb-4 animate-bounce">📦</div>
                        <div class="text-slate-400 font-bold text-lg uppercase tracking-wide">Tidak Ada Item Ditemukan</div>
                        <p class="text-slate-500 mt-1 text-sm max-w-sm mx-auto">Etalase di tab ini masih kosong melompong. Yuk
                            tambah produk baru biar daganganmu makin laku!</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-6 flex justify-center">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection