@extends('layouts.app')
@section('title', 'Checkout — Lapak Gaming')

@push('styles')
<style>
  body { background-color: #f5f5f5; }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    
    {{-- Header --}}
    <h1 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
        <svg class="w-6 h-6 text-itemku-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        Checkout Pesanan
    </h1>

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex gap-3">
        <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
            <h3 class="text-red-800 font-bold text-sm mb-1">Pemesanan Gagal:</h3>
            <ul class="list-disc list-inside space-y-1 text-red-600 text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <form action="{{ route('cart.store') }}" method="POST">
        @csrf
        <input type="hidden" name="payment_method" value="balance">
        
        <div class="grid md:grid-cols-[1fr_340px] gap-6">
            
            {{-- Left Column: Items & Payment --}}
            <div class="space-y-6">
                
                {{-- Items --}}
                <div class="surface-panel border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="bg-gray-50 border-b border-gray-200 px-5 py-3">
                        <h2 class="font-bold text-gray-800 text-sm">Item yang Dibeli</h2>
                    </div>
                    <div class="p-5 space-y-5">
                        @foreach($cartItems as $item)
                            <div class="flex gap-4">
                                <img src="{{ $item->product->image_url }}" class="w-24 sm:w-28 aspect-[16/9] rounded-lg object-cover border border-gray-100" alt="">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800 text-sm leading-tight mb-1">{{ $item->product->name }}</p>
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-[10px] bg-blue-50 text-itemku-blue px-2 py-0.5 rounded font-bold">{{ $item->product->category->name ?? 'Game' }}</span>
                                        <span class="text-[10px] text-gray-500">Toko: {{ $item->product->seller?->store_name ?? $item->product->seller?->name ?? 'Unknown' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-bold text-gray-900">Rp {{ number_format($item->product->price, 0, ',', '.') }} <span class="text-xs font-normal text-gray-500">× {{ $item->quantity }}</span></p>
                                        <p class="text-sm font-bold text-itemku-blue">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                            @if(!$loop->last) <hr class="border-gray-100"> @endif
                        @endforeach
                    </div>
                </div>

                {{-- Payment Method --}}
                {{-- Catatan Pesanan --}}
                <div class="surface-panel border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="bg-gray-50 border-b border-gray-200 px-5 py-3">
                        <h2 class="font-bold text-gray-800 text-sm">
                            Catatan Pesanan
                        </h2>
                    </div>

                    <div class="p-5">
                        <textarea
                            name="buyer_note"
                            rows="4"
                            placeholder="Contoh: Kirim cepat bang, login via Google, dll."
                            class="input resize-none"
                        >{{ old('buyer_note') }}</textarea>
                    </div>
                </div>
                <div class="surface-panel border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="bg-gray-50 border-b border-gray-200 px-5 py-3">
                        <h2 class="font-bold text-gray-800 text-sm">Metode Pembayaran</h2>
                    </div>
                    <div class="p-5">
                        <label class="flex items-center gap-4 p-4 border-2 border-itemku-blue rounded-xl bg-blue-50/30 cursor-pointer relative">
                            <input type="radio" checked class="w-4 h-4 text-itemku-blue">
                            <div class="flex-1">
                                <div class="font-bold text-gray-800 text-sm mb-0.5">Saldo Lapak Gaming (Dompet)</div>
                                <div class="text-xs text-gray-500">Saldo saat ini: <span class="font-bold {{ auth()->user()->balance < $total ? 'text-red-500' : 'text-green-600' }}">Rp {{ number_format(auth()->user()->balance, 0, ',', '.') }}</span></div>
                            </div>
                            @if(auth()->user()->balance < $total)
                                <a href="{{ route('wallet.index') }}" class="absolute right-4 text-xs font-bold text-itemku-blue hover:underline surface-weak px-3 py-1.5 rounded-lg border border-gray-200">Isi Saldo</a>
                            @endif
                        </label>
                    </div>
                </div>

            </div>

            {{-- Right Column: Summary --}}
            <div>
                <div class="surface-panel border border-gray-200 rounded-xl shadow-sm p-5 sticky top-24">
                    <h2 class="font-bold text-gray-800 text-sm mb-4 pb-3 border-b border-gray-100">Ringkasan Belanja</h2>
                    
                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Total Harga ({{ $cartItems->sum('quantity') }} Item)</span>
                            <span class="font-semibold text-gray-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Biaya Layanan</span>
                            <span class="font-semibold text-gray-800">Rp {{ number_format($fee, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center py-4 border-t border-b border-gray-100 mb-6">
                        <span class="font-bold text-gray-800">Total Tagihan</span>
                        <span class="text-lg font-bold text-itemku-blue">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    @if(auth()->user()->balance >= $total)
                        <button type="submit" class="w-full bg-itemku-blue hover:bg-blue-800 text-white font-bold py-3.5 rounded-xl transition-colors shadow-sm">
                            Bayar Sekarang
                        </button>
                    @else
                        <button type="button" disabled class="w-full bg-gray-300 text-gray-500 font-bold py-3.5 rounded-xl cursor-not-allowed">
                            Saldo Tidak Cukup
                        </button>
                    @endif
                    
                    <p class="text-center text-[10px] text-gray-400 mt-4 flex items-center justify-center gap-1">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Pembayaran 100% Aman
                    </p>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection