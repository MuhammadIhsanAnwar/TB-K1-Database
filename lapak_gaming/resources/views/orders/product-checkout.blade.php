@extends('layouts.app')

@section('title', 'Checkout Produk')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10" id="checkout-page"
     data-base-price="{{ (float) $product->price }}"
     data-fee-percent="{{ $feePercent }}">
    <div class="mb-7 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-display font-semibold uppercase tracking-[0.24em] text-brand-400">Checkout</p>
            <h1 class="mt-2 font-display text-3xl font-extrabold text-white">Pesan Produk</h1>
            <p class="mt-2 text-sm text-slate-400">Periksa pesanan dan pilih metode pembayaran sebelum membuat transaksi.</p>
        </div>
        <a href="{{ route('products.show', $product->slug) }}" class="btn-ghost rounded-xl px-4 py-2 text-sm">Kembali ke Produk</a>
    </div>

    <form method="POST" action="{{ route('checkout.store') }}" class="grid gap-6 lg:grid-cols-[1fr_360px]">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <input type="hidden" name="payment_method" value="wallet">

        <div class="space-y-6">
            <div class="card p-5 sm:p-6">
                <h2 class="font-display text-lg font-bold text-white">Produk yang Dipesan</h2>
                <div class="mt-5 flex flex-col gap-4 sm:flex-row">
                    <img src="{{ $product->image_url }}"
                         alt="{{ $product->name }}"
                         class="h-40 w-full rounded-xl object-cover sm:h-32 sm:w-44"
                         loading="lazy"
                         data-fallback="{{ asset('images/default-product.png') }}"
                         onerror="this.onerror=null;this.src=this.dataset.fallback;">

                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            @if($product->category)
                                <span class="badge badge-blue">{{ $product->category->name }}</span>
                            @endif
                            <span class="badge badge-green">Stok {{ $product->stock }}</span>
                        </div>
                        <h3 class="mt-3 text-xl font-bold text-white">{{ $product->name }}</h3>
                        <p class="mt-2 text-sm text-slate-400">Penjual: {{ $product->seller?->name ?? '-' }}</p>
                        <p class="mt-3 font-display text-2xl font-extrabold text-white">
                            Rp {{ number_format((float) $product->price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <div class="mt-6 max-w-xs">
                    <label for="checkout-qty" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Jumlah</label>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="changeCheckoutQty(-1)" class="h-10 w-10 rounded-xl font-bold text-slate-300 transition hover:text-white" style="background:#162032;border:1px solid #1E2D45;">-</button>
                        <input id="checkout-qty" type="number" name="quantity" min="1" max="{{ $product->stock }}" value="{{ $quantity }}"
                               class="input h-10 w-20 rounded-xl py-2 text-center text-sm">
                        <button type="button" onclick="changeCheckoutQty(1)" class="h-10 w-10 rounded-xl font-bold text-slate-300 transition hover:text-white" style="background:#162032;border:1px solid #1E2D45;">+</button>
                    </div>
                </div>
            </div>

            <div class="card p-5 sm:p-6">
                <h2 class="font-display text-lg font-bold text-white">Metode Pembayaran</h2>
                <div class="mt-4 rounded-xl border border-brand-400/20 bg-brand-900/20 px-4 py-4 text-sm text-slate-200">
                    <div class="font-semibold text-white">Wallet</div>
                    <div class="mt-1 text-slate-400">Checkout ini hanya menggunakan saldo wallet.</div>
                    <div class="mt-2 text-xs text-slate-500">Saldo tersedia Rp {{ number_format($availableBalance, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <aside class="card-glow-border h-fit p-6 lg:sticky lg:top-20">
            <h2 class="font-display text-lg font-bold text-white">Ringkasan</h2>
            <div class="mt-5 space-y-3 text-sm">
                <div class="flex items-center justify-between gap-4 text-slate-400">
                    <span>Subtotal</span>
                    <span id="checkout-subtotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between gap-4 text-slate-400">
                    <span>Biaya Platform {{ $feePercent }}%</span>
                    <span id="checkout-fee">Rp {{ number_format($feeAmount, 0, ',', '.') }}</span>
                </div>
                <div class="border-t border-slate-800 pt-4">
                    <div class="flex items-center justify-between gap-4">
                        <span class="font-bold text-white">Total Pembayaran</span>
                        <span id="checkout-total" class="font-display text-xl font-extrabold text-white">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-primary mt-6 w-full rounded-xl py-3.5 text-base">
                Buat Pesanan
            </button>
            <p class="mt-3 text-center text-xs text-slate-500">Pesanan akan masuk ke riwayat transaksi setelah dibuat.</p>
        </aside>
    </form>
</div>
@endsection

@push('scripts')
<script>
const checkoutPage = document.getElementById('checkout-page');
const checkoutQty = document.getElementById('checkout-qty');
const checkoutSubtotal = document.getElementById('checkout-subtotal');
const checkoutFee = document.getElementById('checkout-fee');
const checkoutTotal = document.getElementById('checkout-total');
const checkoutBasePrice = Number(checkoutPage?.dataset.basePrice || 0);
const checkoutFeePercent = Number(checkoutPage?.dataset.feePercent || 0);

function formatRupiah(value) {
    return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
}

function updateCheckoutTotal() {
    const qty = Math.max(1, Math.min(parseInt(checkoutQty.value || '1', 10), parseInt(checkoutQty.max || '99', 10)));
    checkoutQty.value = qty;
    const subtotal = qty * checkoutBasePrice;
    const fee = Math.round((subtotal * checkoutFeePercent / 100) * 100) / 100;
    checkoutSubtotal.textContent = formatRupiah(subtotal);
    checkoutFee.textContent = formatRupiah(fee);
    checkoutTotal.textContent = formatRupiah(subtotal + fee);
}

function changeCheckoutQty(delta) {
    checkoutQty.value = (parseInt(checkoutQty.value || '1', 10) || 1) + delta;
    updateCheckoutTotal();
}

checkoutQty?.addEventListener('input', updateCheckoutTotal);
</script>
@endpush
