@extends('layouts.app')

@section('title', 'Kelola Transaksi')

@section('content')
<div class="min-h-screen bg-slate-950 py-12 px-4">
    <div class="mx-auto max-w-6xl space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white">Kelola Transaksi</h1>
                <p class="mt-2 text-slate-400">Lihat semua order buyer-seller dan status pembayarannya.</p>
            </div>
            <a href="{{ route('admin.orders.report.pdf') }}"
               class="rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-5 py-3 text-sm font-semibold text-emerald-200 transition hover:bg-emerald-500/20">
                Download PDF
            </a>
        </div>

        <div class="overflow-hidden rounded-3xl border border-slate-800 bg-slate-900">
            <table class="min-w-full divide-y divide-slate-800 text-sm text-left text-slate-300">
                <thead class="bg-slate-950 text-xs uppercase tracking-[0.2em] text-slate-500">
                    <tr>
                        <th class="px-6 py-4">Invoice</th>
                        <th class="px-6 py-4">Buyer</th>
                        <th class="px-6 py-4">Seller</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 bg-slate-900">
                    @foreach($orders as $order)
                        <tr>
                            <td class="px-6 py-4">{{ $order->invoice_number }}</td>
                            <td class="px-6 py-4">{{ $order->buyer?->name ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $order->seller?->name ?? '-' }}</td>
                            <td class="px-6 py-4">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">{{ $order->status_label }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.orders.show', $order->order_code) }}" class="rounded-2xl bg-amber-500 px-3 py-2 text-xs font-semibold text-slate-950">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div>{{ $orders->links() }}</div>
    </div>
</div>
@endsection
