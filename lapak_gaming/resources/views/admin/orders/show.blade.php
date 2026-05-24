@extends('layouts.app')

@section('title', 'Detail Transaksi — Admin')

@push('styles')
<style>
    /* ── True Glassmorphism HQ Panel ─────────────────────────── */
    .dashboard-transparent {
        background: transparent !important;
    }
    
    .panel-card-glass {
        background: rgba(10, 17, 30, 0.35) !important;
        backdrop-filter: blur(24px) saturate(160%);
        border: 1px solid rgba(255, 255, 255, 0.06);
        box-shadow: 0 12px 40px 0 rgba(0, 0, 0, 0.5);
    }

    .sub-panel-glass {
        background: rgba(5, 9, 16, 0.45) !important;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    /* ── Typography & Tokens ─────────────────────────────────── */
    .invoice-token {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    /* ── Cyber Status Pills ──────────────────────────────────── */
    .pill-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 14px;
        border-radius: 8px;
        font-size: .75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    
    .status-completed, .status-paid { background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3); color: #34d399; }
    .status-pending, .status-processing { background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.3); color: #fbbf24; }
    .status-cancelled, .status-failed, .status-dispute { background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.3); color: #f87171; }
    .status-default { background: rgba(148, 163, 184, 0.12); border: 1px solid rgba(148, 163, 184, 0.3); color: #cbd5e1; }
</style>
@endpush

@section('content')
<div class="min-h-screen py-10 relative overflow-hidden dashboard-transparent">
    {{-- Ambient Neon Glow Base --}}
    <div class="absolute top-0 right-1/4 w-96 h-96 bg-brand-500/5 rounded-full blur-[150px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-1/4 w-96 h-96 bg-amber-500/5 rounded-full blur-[150px] pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-4 space-y-6 relative z-10">

        {{-- ── HEADER CONTROL ───────────────────────────────────── --}}
        <div class="flex items-center justify-between gap-4 border-b border-white/5 pb-5">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                    <span class="text-xs font-bold uppercase tracking-widest text-amber-500/80">Transaction Audit Dossier</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold invoice-token text-amber-400">
                    {{ $order->invoice_number }}
                </h1>
                <p class="text-slate-400 text-sm mt-0.5">Manifest mutasi finansial dan detail pemenuhan item pesanan.</p>
            </div>
            
            <a href="{{ route('admin.orders.index') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/5 px-4 py-2.5 text-xs font-bold text-slate-300 transition-all tracking-wide shrink-0">
                <svg class="w-4 h-4 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                KEMBALI
            </a>
        </div>

        {{-- ── CARD 1: ACCOUNT ESCROW PARTIES (BUYER & SELLER) ───── --}}
        <div class="grid gap-4 md:grid-cols-2">
            {{-- Buyer Glass Card --}}
            <div class="rounded-3xl p-5 panel-card-glass flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl sub-panel-glass flex items-center justify-center text-lg shrink-0">
                    👤
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] uppercase tracking-wider font-bold text-slate-500 mb-1">Pihak Pembeli (Buyer)</p>
                    <h2 class="text-base font-bold text-white tracking-tight truncate">{{ $order->buyer->name ?? 'Deleted Account' }}</h2>
                    <p class="text-xs text-slate-400 truncate mt-0.5 font-medium">{{ $order->buyer->email ?? '—' }}</p>
                </div>
            </div>

            {{-- Seller Glass Card --}}
            <div class="rounded-3xl p-5 panel-card-glass flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl sub-panel-glass flex items-center justify-center text-lg shrink-0">
                    🏪
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] uppercase tracking-wider font-bold text-slate-500 mb-1">Pihak Penjual (Seller)</p>
                    <h2 class="text-base font-bold text-slate-300 tracking-tight truncate">{{ $order->seller_label === '-' ? 'Deleted Store' : $order->seller_label }}</h2>
                    <p class="text-xs text-slate-400 truncate mt-0.5 font-medium">
                        @if($order->seller && !$order->has_multiple_sellers)
                            {{ $order->seller->email ?? '—' }}
                        @elseif($order->has_multiple_sellers)
                            Beberapa seller terlibat
                        @else
                            —
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- ── CARD 2A: TRANSACTION TIME ─────────────────────────── --}}
        <div class="rounded-3xl p-5 panel-card-glass flex flex-col gap-2">
            <p class="text-[10px] uppercase tracking-wider font-bold text-slate-500">Tanggal Transaksi</p>
            <div class="text-base sm:text-lg font-bold text-white tracking-tight">
                {{ $order->created_at?->translatedFormat('d F Y H:i') }}
            </div>
        </div>

        {{-- ── CARD 2: MANIFEST ITEM PESANAN ─────────────────────── --}}
        <div class="rounded-3xl panel-card-glass overflow-hidden">
            <div class="px-6 py-4 border-b border-white/5 bg-white/5 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                Aset Komoditas & Kuantitas Item
            </div>
            
            <div class="divide-y divide-white/5">
                @foreach($order->items as $item)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 py-5 hover:bg-black/10 transition-colors">
                        <div class="flex items-center gap-3.5">
                            <div class="w-9 h-9 rounded-xl sub-panel-glass flex items-center justify-center text-sm font-bold text-amber-400 font-mono shrink-0">
                                x{{ $item->quantity }}
                            </div>
                            <div>
                                <h3 class="font-bold text-white text-base tracking-tight">{{ $item->product_name }}</h3>
                                <p class="text-xs text-slate-500 mt-0.5 font-medium">Kuantitas Pembelian: {{ $item->quantity }} Item</p>
                            </div>
                        </div>
                        <div class="text-left sm:text-right font-mono font-bold text-white text-sm bg-black/20 sm:bg-transparent px-3 py-1.5 sm:p-0 rounded-lg border border-white/5 sm:border-none w-max">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ── CARD 3: AUDIT SUMMARY & FINANCIAL ACCESS ──────────── --}}
        <div class="rounded-3xl p-6 panel-card-glass">
            <div class="grid gap-6 sm:grid-cols-2">
                {{-- Grand Total Summary (FIXED COLOR Legibility) --}}
                <div class="p-4 rounded-2xl sub-panel-glass border border-white/5">
                    <p class="text-[10px] uppercase tracking-wider font-bold text-slate-500 mb-1.5">Total Mutasi Keuangan</p>
                    <div class="text-2xl font-extrabold text-emerald-400 font-mono tracking-tight">
                        Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                    </div>
                </div>

                {{-- Payment Proof --}}
                @if($order->payment_proof)
                <div class="p-4 rounded-2xl sub-panel-glass border border-white/5 flex flex-col justify-between items-start gap-2 col-span-full sm:col-span-1">
                    <p class="text-[10px] uppercase tracking-wider font-bold text-slate-500">Bukti Pembayaran</p>
                    <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="block w-full h-32 rounded-lg overflow-hidden border border-white/10 hover:border-itemku-blue transition-colors">
                        <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Payment Proof" class="w-full h-full object-cover">
                    </a>
                </div>
                @endif

                {{-- Status Badging --}}
                <div class="p-4 rounded-2xl sub-panel-glass border border-white/5 flex flex-col justify-between items-start gap-2">
                    <p class="text-[10px] uppercase tracking-wider font-bold text-slate-500">Status Validasi Alur</p>
                    @php
                        $cleanStatus = strtolower($order->getAttributes()['status'] ?? '');
                        if (in_array($cleanStatus, ['completed', 'paid', 'success'])) {
                            $statusClass = 'status-completed';
                        } elseif (in_array($cleanStatus, ['pending', 'processing', 'unpaid'])) {
                            $statusClass = 'status-pending';
                        } elseif (in_array($cleanStatus, ['cancelled', 'failed', 'dispute', 'rejected'])) {
                            $statusClass = 'status-cancelled';
                        } else {
                            $statusClass = 'status-default';
                        }
                    @endphp
                    <span class="pill-status {{ $statusClass }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                        {{ $order->status_label }}
                    </span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection