@extends('layouts.app')

@section('title', 'Checkout Produk')

@push('styles')
<style>
  .checkout-shell {
    background:
      radial-gradient(circle at top left, rgba(56, 189, 248, 0.16), transparent 28%),
      radial-gradient(circle at top right, rgba(249, 115, 22, 0.10), transparent 24%),
      linear-gradient(180deg, #050816 0%, #0a1120 100%);
  }

  .checkout-panel {
    background: linear-gradient(180deg, rgba(9, 18, 37, 0.96), rgba(7, 12, 24, 0.96));
    border: 1px solid rgba(255, 255, 255, 0.08);
    box-shadow: 0 24px 80px rgba(37, 99, 235, 0.12);
  }
</style>
@endpush

@section('content')
<div class="checkout-shell min-h-[calc(100vh-80px)] py-10" id="checkout-page"
     data-base-price="{{ (float) $product->price }}"
     data-fee-percent="{{ $feePercent }}">
    <div class="mx-auto max-w-6xl px-4">
    <div class="mb-7 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-display font-semibold uppercase tracking-[0.24em] text-sky-300">Checkout</p>
            <h1 class="mt-2 font-display text-3xl font-black text-white md:text-4xl">Pesan Produk</h1>
            <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-400">Periksa pesanan, tambahkan catatan jika perlu, dan pilih metode pembayaran sebelum membuat transaksi.</p>
        </div>
        <a href="{{ route('products.show', $product->slug) }}" class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/[0.03] px-4 py-2 text-sm font-semibold text-slate-200 transition hover:border-sky-400/20 hover:bg-sky-500/10 hover:text-sky-200">Kembali ke Produk</a>
    </div>

    <form method="POST" action="{{ route('checkout.store') }}" class="grid gap-6 lg:grid-cols-[1fr_380px]">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <input type="hidden" name="payment_method" value="balance">

        <div class="space-y-6">
            <div class="checkout-panel rounded-[30px] p-5 sm:p-6">
                <h2 class="font-display text-lg font-bold text-white">Produk yang Dipesan</h2>
                <div class="mt-5 flex flex-col gap-4 sm:flex-row">
                    <img src="{{ $product->image_url }}"
                        alt="{{ $product->name }}"
                        class="w-full rounded-[24px] object-cover aspect-[16/9] sm:w-44 border border-white/10"
                         loading="lazy"
                         data-fallback="{{ asset('images/default-product.png') }}"
                         onerror="this.onerror=null;this.src=this.dataset.fallback;">

                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            @if($product->category)
                                <span class="rounded-full bg-sky-500/10 px-2.5 py-1 text-[10px] font-bold text-sky-300">{{ $product->category->name }}</span>
                            @endif
                            <span class="rounded-full bg-emerald-500/10 px-2.5 py-1 text-[10px] font-bold text-emerald-300">Stok {{ $product->stock }}</span>
                        </div>
                        <h3 class="mt-3 font-display text-2xl font-black text-white">{{ $product->name }}</h3>
                        <p class="mt-2 text-sm text-slate-400">Penjual: {{ $product->seller?->name ?? '-' }}</p>
                        <p class="mt-3 font-display text-2xl font-black text-sky-300">
                            Rp {{ number_format((float) $product->price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <div class="mt-6 max-w-xs">
                    <label for="checkout-qty" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Jumlah</label>
                    <div class="flex items-center gap-3 rounded-[24px] border border-white/10 bg-white/[0.03] p-2">
                        <button type="button" onclick="changeCheckoutQty(-1)" class="flex h-10 w-10 items-center justify-center rounded-xl font-bold text-slate-400 transition hover:bg-white/5 hover:text-sky-300">-</button>
                        <input id="checkout-qty" type="number" name="quantity" min="1" max="{{ $product->stock }}" value="{{ $quantity }}"
                               class="input h-10 w-20 rounded-xl border border-white/10 bg-white/[0.03] py-2 text-center text-sm text-white">
                        <button type="button" onclick="changeCheckoutQty(1)" class="flex h-10 w-10 items-center justify-center rounded-xl font-bold text-slate-400 transition hover:bg-white/5 hover:text-sky-300">+</button>
                    </div>
                </div>
            </div>

            <div class="checkout-panel rounded-[30px] p-5 sm:p-6">
                <h2 class="font-display text-lg font-bold text-white">Metode Pembayaran</h2>
                <div class="mt-4 rounded-[24px] border border-sky-400/15 bg-sky-500/8 px-4 py-4 text-sm text-slate-200">
                    <div class="font-semibold text-white">Wallet</div>
                    <div class="mt-1 text-slate-400">Checkout ini hanya menggunakan saldo wallet.</div>
                    <div class="mt-2 text-xs text-slate-500">Saldo tersedia Rp {{ number_format($availableBalance, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="checkout-panel rounded-[30px] p-5 sm:p-6">
                <h2 class="font-display text-lg font-bold text-white">Catatan Pesanan</h2>
                <p class="mt-2 text-sm text-slate-400">Opsional, gunakan untuk instruksi tambahan ke seller.</p>
                <textarea
                    name="buyer_note"
                    rows="4"
                    maxlength="1000"
                    placeholder="Contoh: Kirim cepat, bantu panduan aktivasi, dsb."
                    class="input mt-4 resize-none rounded-[22px] border border-white/10 bg-white/[0.03] px-4 py-3 text-white placeholder:text-slate-500"
                >{{ old('buyer_note') }}</textarea>
            </div>
        </div>

        <aside class="checkout-panel h-fit rounded-[30px] p-6 lg:sticky lg:top-20">
            <h2 class="font-display text-lg font-bold text-white">Ringkasan</h2>
            <div class="mt-5 space-y-3 text-sm">
                <div class="flex items-center justify-between gap-4 text-slate-400">
                    <span>Subtotal</span>
                    <span id="checkout-subtotal" class="font-semibold text-white">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between gap-4 text-slate-400">
                    <span>Biaya Platform {{ $feePercent }}%</span>
                    <span id="checkout-fee" class="font-semibold text-white">Rp {{ number_format($feeAmount, 0, ',', '.') }}</span>
                </div>
                <div class="rounded-[24px] border border-white/10 bg-white/[0.03] p-4 pt-4">
                    <div class="flex items-center justify-between gap-4">
                        <span class="font-bold text-white">Total Pembayaran</span>
                        <span id="checkout-total" class="font-display text-2xl font-black text-sky-300">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <button type="submit" class="mt-6 w-full rounded-2xl bg-gradient-to-r from-sky-500 to-blue-600 py-3.5 text-base font-bold text-white shadow-[0_16px_30px_rgba(37,99,235,0.28)] transition hover:-translate-y-0.5 hover:from-sky-400 hover:to-blue-500">
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
