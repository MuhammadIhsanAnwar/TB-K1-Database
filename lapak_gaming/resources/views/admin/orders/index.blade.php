@extends('layouts.app')

@section('title', 'Kelola Transaksi — Admin')

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

    /* ── Monospace Invoice Tokens ────────────────────────────── */
    .invoice-token {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-weight: 700;
        letter-spacing: -0.02em;
    }

    /* ── Cyber Status Pills for Orders ───────────────────────── */
    .pill-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 8px;
        font-size: .75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.02em;
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
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-emerald-500/5 rounded-full blur-[150px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-brand-500/5 rounded-full blur-[150px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 space-y-6 relative z-10">

        {{-- ── HEADER CONTROL ───────────────────────────────────── --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-white/5 pb-5">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-bold uppercase tracking-widest text-emerald-400/90">Financial Ledgers & Orders</span>
                </div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">Kelola Transaksi</h1>
                <p class="text-slate-400 text-sm mt-0.5">Pantau rekaman mutasi order buyer-seller beserta status sinkronisasi payment gateway.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.dashboard') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/5 px-4 py-2.5 text-xs font-bold text-slate-300 transition-all tracking-wide">
                    Dashboard
                </a>
                <a href="{{ route('admin.orders.report.pdf') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 px-4 py-2.5 text-xs font-bold text-slate-950 transition-all shadow-md tracking-wide shadow-emerald-500/10">
                    <svg class="w-4 h-4 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    DOWNLOAD REPORT PDF
                </a>
            </div>
        </div>

        {{-- ── MAIN LEDGER TABLE PANEL ─────────────────────────── --}}
        <div class="rounded-3xl panel-card-glass overflow-hidden">
            {{-- 🛠️ PERBAIKAN: Diubah jadi flex-col di mobile agar tidak tabrakan/terpotong --}}
            <div class="px-6 py-4 border-b border-white/5 bg-white/5 font-medium text-slate-400 text-sm flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <span>Menampilkan log perputaran invoice order marketplace.</span>
                <span class="text-xs font-mono bg-black/20 text-slate-500 px-2 py-0.5 rounded border border-white/5 w-max">Total: {{ $orders->total() }} Records</span>
            </div>

            @if($orders->isEmpty())
                <div class="py-20 text-center text-slate-500">
                    <div class="text-4xl mb-3">🧾</div>
                    <p class="text-base font-bold text-slate-400">Belum ada transaksi terekam</p>
                    <p class="text-xs text-slate-600 mt-1">Seluruh invoices dari checkout pengguna akan muncul di sini.</p>
                </div>
            @else
                <div class="overflow-x-auto scroller-clean">
                    <table class="min-w-full divide-y divide-white/5 text-sm text-left text-slate-300">
                        <thead class="bg-black/30 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-white/5">
                            <tr>
                                <th class="px-6 py-4">Nomor Invoice</th>
                                <th class="px-6 py-4">Pihak Buyer</th>
                                <th class="px-6 py-4">Pihak Seller</th>
                                <th class="px-6 py-4">Grand Total</th>
                                <th class="px-6 py-4">Status Alur</th>
                                <th class="px-6 py-4 text-right">Otoritas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($orders as $order)
                                <tr class="hover:bg-white/5 transition-colors">
                                    {{-- Invoice ID --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span class="text-amber-400 invoice-token text-sm tracking-wide">{{ $order->invoice_number }}</span>
                                            <span class="text-[10px] text-slate-500 font-mono mt-0.5 uppercase">Code: {{ $order->order_code }}</span>
                                        </div>
                                    </td>
                                    
                                    {{-- 🛠️ PERBAIKAN: Hapus whitespace-nowrap agar nama panjang buyer bisa nge-wrap otomatis jika layar sempit --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2 max-w-[180px]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-400 shrink-0"></span>
                                            <span class="font-bold text-white tracking-tight break-words">{{ $order->buyer?->name ?? 'Deleted User' }}</span>
                                        </div>
                                    </td>
                                    
                                    {{-- 🛠️ PERBAIKAN: Hapus whitespace-nowrap agar nama toko tidak memaksa tabel molor --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2 max-w-[180px]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-purple-400 shrink-0"></span>
                                            <span class="font-bold text-slate-300 tracking-tight break-words">{{ $order->seller?->name ?? 'Deleted Store' }}</span>
                                        </div>
                                    </td>
                                    
                                    {{-- Grand Total --}}
                                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-white font-mono text-sm">
                                        Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                    </td>
                                    
                                    {{-- Dynamic Status Pill --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
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
                                    </td>
                                    
                                    {{-- Action Trigger --}}
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <a href="{{ route('admin.orders.show', $order->order_code) }}" 
                                           class="inline-flex items-center justify-center rounded-xl bg-brand-500/10 border border-brand-500/25 px-4 py-2 text-xs font-bold text-brand-400 hover:bg-brand-500 hover:text-white transition-all shadow-sm">
                                            Buka Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links Wrapper --}}
                <div class="px-6 py-4 border-t border-white/5 flex justify-center">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection