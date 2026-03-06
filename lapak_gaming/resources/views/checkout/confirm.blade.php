@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Konfirmasi Checkout</h1>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="font-semibold">{{ $product->name }}</p>
        <p class="mt-2">Total: <span class="font-bold">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span></p>
        <p class="mt-2 text-sm text-gray-600">Saldo wallet: Rp {{ number_format($wallet->balance ?? 0, 0, ',', '.') }}</p>
        <form method="POST" action="{{ route('checkout.process', $product->slug) }}" class="mt-6">
            @csrf
            <button class="px-5 py-2 bg-indigo-600 text-white rounded-lg">Lanjutkan Pembayaran</button>
        </form>
    </div>
</div>
@endsection
