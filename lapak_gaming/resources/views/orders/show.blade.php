@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')

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

<div class="min-h-screen bg-[#060816] py-12 px-4">

    <div class="mx-auto max-w-5xl space-y-7">

        {{-- MAIN CARD --}}
        <div class="reveal relative overflow-hidden rounded-[30px] border border-blue-500/20 bg-gradient-to-br from-[#060816] via-[#091225] to-[#0B1730] p-8 shadow-[0_0_80px_rgba(37,99,235,0.12)]">

            {{-- GLOW --}}
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(37,99,235,0.18),transparent_35%)]"></div>

            <div class="relative z-10">

                {{-- HEADER --}}
                <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">

                    <div>
                        <div class="inline-flex items-center gap-2 rounded-full border border-blue-500/30 bg-blue-500/10 px-4 py-2 text-[11px] font-bold tracking-[0.2em] text-blue-300 backdrop-blur-xl">
                            <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            TRANSACTION DETAIL
                        </div>

                        <h1 class="mt-5 text-3xl font-black leading-tight text-white md:text-5xl">
                            {{ $order->invoice_number }}
                        </h1>

                        <p class="mt-3 text-sm text-slate-400">
                            Status:
                            <span class="font-semibold text-blue-300">
                                {{ $order->status_label }}
                            </span>
                        </p>
                    </div>

                    <div class="rounded-[26px] border border-white/10 surface-weak px-6 py-5 backdrop-blur-xl">
                        <div class="text-sm text-slate-400">
                            Grand Total
                        </div>

                        <div class="mt-2 text-3xl font-black text-white">
                            Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                        </div>
                    </div>

                </div>

                {{-- BUYER & SELLER --}}
                @php
                    $orderSellers = collect();

                    if ($order->seller) {
                        $orderSellers = collect([$order->seller]);
                    } else {
                        $orderSellers = $order->items->map(fn($item) => $item->product?->seller)
                            ->filter()
                            ->unique('id')
                            ->values();
                    }
                @endphp

                <div class="mt-8 grid gap-5 md:grid-cols-2">

                    <div class="reveal reveal-delay-1 rounded-[26px] border border-white/5 surface-weak p-6 transition duration-300 hover:border-blue-500/30 hover:bg-blue-500/[0.04]">

                        <div class="text-lg font-black text-white">
                            Buyer
                        </div>

                        <div class="mt-4 space-y-1">
                            <div class="text-sm font-semibold text-slate-300">
                                {{ $order->buyer->name ?? '-' }}
                            </div>

                            <div class="text-sm text-slate-500">
                                {{ $order->buyer->email ?? '-' }}
                            </div>
                        </div>

                    </div>

                    <div class="reveal reveal-delay-2 rounded-[26px] border border-white/5 surface-weak p-6 transition duration-300 hover:border-blue-500/30 hover:bg-blue-500/[0.04]">

                        <div class="text-lg font-black text-white">
                            Seller
                        </div>

                        <div class="mt-4 space-y-1">
                            <div class="text-sm font-semibold text-slate-300">
                                {{ $order->seller_label }}
                            </div>

                            <div class="text-sm text-slate-500">
                                @if($orderSellers->count() === 1)
                                    {{ $orderSellers->first()->email ?? '-' }}
                                @elseif($orderSellers->count() > 1)
                                    {{ $orderSellers->count() }} seller berbeda
                                @else
                                    -
                                @endif
                            </div>
                        </div>

                    </div>

                </div>

                {{-- ORDER ITEMS --}}
                <div class="reveal reveal-delay-2 mt-8 overflow-hidden rounded-[26px] border border-blue-500/20 bg-[#0B1220]/95 shadow-[0_0_40px_rgba(37,99,235,0.06)]">

                    <div class="border-b border-white/5 surface-weak px-6 py-5">

                        <div class="text-[11px] font-bold uppercase tracking-[0.25em] text-blue-300">
                            Pesanan
                        </div>

                    </div>

                    <div class="divide-y divide-white/5">

                        @foreach($order->items as $item)

                            @php
                                $itemName = $item->name_snapshot ?? $item->product?->name ?? 'Produk';
                                $itemSubtotal = (float) $item->price_snapshot * (int) $item->quantity;
                            @endphp

                            <div class="flex flex-col gap-4 px-6 py-5 transition duration-300 hover:bg-blue-500/[0.04] md:flex-row md:items-center md:justify-between">

                                <div>
                                    <div class="text-base font-bold text-white">
                                        {{ $itemName }}
                                    </div>

                                    <div class="mt-1 text-sm text-slate-400">
                                        Seller: {{ $item->product?->seller->name ?? $item->seller?->name ?? 'Unknown' }}
                                    </div>

                                    <div class="mt-1 text-sm text-slate-400">
                                        Jumlah: {{ $item->quantity }}
                                    </div>
                                </div>

                                <div class="text-lg font-black text-white">
                                    Rp {{ number_format($itemSubtotal, 0, ',', '.') }}
                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

                {{-- PAYMENT INFO --}}
                <div class="mt-8 grid gap-5 md:grid-cols-2">

                    <div class="rounded-[26px] border border-white/5 surface-weak p-6 transition duration-300 hover:border-blue-500/30 hover:bg-blue-500/[0.04]">

                        <div class="text-sm text-slate-400">
                            Metode Pembayaran
                        </div>

                        <div class="mt-3 text-lg font-bold text-white">
                            {{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'Belum dipilih')) }}
                        </div>

                    </div>

                    <div class="rounded-[26px] border border-white/5 surface-weak p-6 transition duration-300 hover:border-blue-500/30 hover:bg-blue-500/[0.04]">

                        <div class="text-sm text-slate-400">
                            Tanggal Pesanan
                        </div>

                        <div class="mt-3 text-lg font-bold text-white">
                            {{ $order->created_at->translatedFormat('d F Y H:i') }}
                        </div>

                    </div>

                </div>

                {{-- SELLER ACTIONS --}}
                @php
                    $isOrderSeller = $order->seller_id === auth()->id()
                        || $order->items->pluck('product.seller_id')->filter()->contains(auth()->id());
                @endphp

                @if($isOrderSeller)
                    <div class="reveal reveal-delay-3 mt-8 rounded-[26px] border border-white/5 surface-weak p-6">
                        <h2 class="text-xl font-black text-white">
                            Aksi Seller
                        </h2>

                        <p class="mt-3 text-sm text-slate-400">
                            Ubah status order buyer sesuai tahap proses transaksi.
                        </p>

                        <div class="mt-6 grid gap-4 md:grid-cols-2">
                            @if($order->status === \App\Models\Order::STATUS_PAYMENT_UPLOADED)
                                <form action="{{ route('seller.orders.process', $order) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full rounded-[26px] border border-amber-500/20 bg-amber-500/10 px-5 py-4 text-base font-bold text-amber-300 transition duration-300 hover:-translate-y-1 hover:bg-amber-500/20 hover:shadow-[0_0_25px_rgba(245,158,11,0.15)]">
                                        Tandai Diproses
                                    </button>
                                </form>
                            @endif

                            @if($order->status === \App\Models\Order::STATUS_PROCESSING)
                                <form action="{{ route('seller.orders.deliver', $order) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full rounded-[26px] border border-emerald-500/20 bg-emerald-500/10 px-5 py-4 text-base font-bold text-emerald-300 transition duration-300 hover:-translate-y-1 hover:bg-emerald-500/20 hover:shadow-[0_0_25px_rgba(16,185,129,0.15)]">
                                        Tandai Sudah Dikirim
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- ACTION BUTTONS --}}
                <div class="relative z-10 mt-8 grid gap-4 md:grid-cols-2">

                    <a href="{{ route('chat.order', $order) }}"
                       class="flex items-center justify-center rounded-[26px] border border-blue-500/20 bg-blue-500/10 px-5 py-4 text-base font-bold text-blue-300 transition duration-300 hover:-translate-y-1 hover:border-blue-400/40 hover:bg-blue-500/20 hover:shadow-[0_0_25px_rgba(59,130,246,0.15)]">

                        Chat dengan Seller

                    </a>

                    @if($order->buyer_id == auth()->id() && in_array($order->status, [\App\Models\Order::STATUS_COMPLETED, \App\Models\Order::STATUS_DELIVERED], true))
                        <a href="{{ route('orders.receipt.pdf', $order->order_code) }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="flex items-center justify-center rounded-[26px] border border-emerald-500/20 bg-emerald-500/10 px-5 py-4 text-base font-bold text-emerald-300 transition duration-300 hover:-translate-y-1 hover:border-emerald-400/40 hover:bg-emerald-500/20 hover:shadow-[0_0_25px_rgba(16,185,129,0.15)]">

                            Unduh Kwitansi PDF

                        </a>
                    @endif

                    @if(in_array($order->status, [\App\Models\Order::STATUS_PENDING_PAYMENT, \App\Models\Order::STATUS_PAYMENT_UPLOADED], true))

                        <form action="{{ route('orders.cancel', $order) }}" method="POST">
                            @csrf

                            <button type="submit"
                                class="w-full rounded-[26px] border border-red-500/20 bg-red-500/10 px-5 py-4 text-base font-bold text-red-300 transition duration-300 hover:-translate-y-1 hover:bg-red-500/20 hover:shadow-[0_0_25px_rgba(239,68,68,0.15)]">

                                Batalkan Order

                            </button>

                        </form>

                    @endif

                </div>

                {{-- PAYMENT PROOF --}}
                @if($order->payment_proof)

                <div class="reveal reveal-delay-3 mt-8 rounded-[26px] border border-white/5 surface-weak p-6">

                    <h2 class="text-xl font-black text-white">
                        Bukti Pembayaran
                    </h2>

                    <div class="mt-5 overflow-hidden rounded-[24px] border border-white/10">

                        <a href="{{ asset('storage/' . $order->payment_proof) }}"
                           target="_blank"
                           rel="noopener noreferrer">

                            <img
                                src="{{ asset('storage/' . $order->payment_proof) }}"
                                alt="Bukti Pembayaran"
                                class="w-full object-cover transition duration-300 hover:scale-[1.01] max-h-[500px]"
                            />

                        </a>

                    </div>

                </div>

                @elseif($order->status === \App\Models\Order::STATUS_PENDING_PAYMENT)

                {{-- PAYMENT FORM --}}
                <div class="reveal reveal-delay-3 mt-8 rounded-[26px] border border-white/5 surface-weak p-6">

                    <h2 class="text-xl font-black text-white">
                        Lanjutkan Pembayaran
                    </h2>

                    <p class="mt-3 text-sm text-slate-400">
                        Metode pembayaran:
                        <span class="font-semibold text-white">
                            {{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'Belum dipilih')) }}
                        </span>
                    </p>

                    @if($order->payment_method === 'balance')

                        <form action="{{ route('orders.pay', $order) }}" method="POST" class="mt-6">
                            @csrf

                            <input type="hidden" name="payment_method" value="balance">

                            <button type="submit"
                                class="w-full rounded-[26px] border border-emerald-500/20 bg-emerald-500/10 px-5 py-4 text-base font-bold text-emerald-300 transition duration-300 hover:-translate-y-1 hover:bg-emerald-500/20 hover:shadow-[0_0_25px_rgba(16,185,129,0.15)]">

                                Bayar dengan Saldo

                            </button>

                        </form>

                    @else

                        <form action="{{ route('orders.proof', $order) }}"
                              method="POST"
                              enctype="multipart/form-data"
                              class="mt-6 space-y-5">

                            @csrf

                            <label class="block text-sm font-medium text-slate-300">

                                Unggah bukti pembayaran

                                <input type="file"
                                       name="payment_proof"
                                       accept="image/*"
                                       required
                                       class="mt-3 w-full rounded-[22px] border border-white/10 surface-weak px-4 py-3 text-slate-100 file:mr-4 file:rounded-xl file:border-0 file:bg-blue-500/20 file:px-4 file:py-2 file:font-semibold file:text-blue-300" />

                            </label>

                            <button type="submit"
                                class="w-full rounded-[26px] border border-blue-500/20 bg-blue-500/10 px-5 py-4 text-base font-bold text-blue-300 transition duration-300 hover:-translate-y-1 hover:border-blue-400/40 hover:bg-blue-500/20 hover:shadow-[0_0_25px_rgba(59,130,246,0.15)]">

                                Kirim Bukti Pembayaran

                            </button>

                        </form>

                    @endif

                </div>

                @endif

            </div>

        </div>

    </div>
</div>

@endsection
