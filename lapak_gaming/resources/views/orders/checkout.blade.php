@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold text-white mb-8">Checkout</h1>

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-900/30 border border-red-700 rounded-xl">
        <h3 class="text-red-300 font-bold mb-2">Terjadi Kesalahan:</h3>
        <ul class="list-disc list-inside space-y-1 text-red-200 text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 mb-6">
            <h2 class="font-bold text-white mb-4">Item yang Dibeli</h2>
            @foreach($cartItems as $item)
                <div class="flex gap-4 py-3 border-b border-gray-800 last:border-0">
                    <img src="{{ $item->product->image_url }}" class="w-14 h-14 rounded-lg object-cover" alt="">
                    <div class="flex-1">
                        <p class="text-sm text-gray-200">{{ $item->product->name }}</p>
                        <p class="text-xs text-gray-500">× {{ $item->quantity }}</p>
                    </div>
                    <p class="text-sm text-white font-bold">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</p>
                </div>
            @endforeach
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 mb-6">
            <h2 class="font-bold text-white mb-4">Metode Pembayaran</h2>
            <div class="grid grid-cols-3 gap-3">
                @foreach(['balance' => 'Saldo ('.'Rp '.number_format(auth()->user()->balance,0,',','.').')', 'transfer' => 'Transfer Bank', 'qris' => 'QRIS', 'gopay' => 'GoPay', 'ovo' => 'OVO', 'dana' => 'DANA'] as $val => $label)
                    <label class="cursor-pointer">
                        <input type="radio" name="payment_method" value="{{ $val }}" class="sr-only peer" {{ $val === 'balance' ? 'checked' : '' }}>
                        <div class="border border-gray-700 peer-checked:border-violet-500 peer-checked:bg-violet-900/30 rounded-xl p-3 text-center text-sm text-gray-400 peer-checked:text-white transition">
                            {{ $label }}
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 mb-6">
            <div class="flex justify-between text-sm text-gray-400 mb-2">
                <span>Subtotal</span>
                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm text-gray-400 mb-2">
                <span>Biaya Platform</span>
                <span>Rp {{ number_format($fee, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-base font-bold text-white">
                <span>Total Pembayaran</span>
                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>

        <button type="submit" class="w-full bg-violet-600 hover:bg-violet-700 text-white font-bold py-4 rounded-xl text-lg transition">
            Buat Pesanan →
        </button>
    </form>
</div>
@endsection