@extends('layouts.app')

@section('title', 'Detail Order')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Detail Order {{ $order->order_number ?? '' }}</h1>
    <div class="bg-white rounded-lg shadow p-6">
        <p>Status: <span class="font-semibold">{{ $order->status }}</span></p>
        <p class="mt-2">Total: Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}</p>
    </div>
</div>
@endsection
