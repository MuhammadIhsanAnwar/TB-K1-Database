@extends('layouts.app')

@section('title', 'Chat Order')

@section('content')
<div class="min-h-screen bg-slate-950 text-white p-10">
    <h1 class="text-3xl font-bold">
        Chat dengan Seller
    </h1>

    <div class="mt-6 rounded-3xl bg-slate-900 p-6">
        <p>Invoice: {{ $order->invoice_number }}</p>
        <p>Buyer: {{ $order->buyer->name ?? '-' }}</p>
        <p>Seller: {{ $order->seller->name ?? '-' }}</p>
    </div>
</div>
@endsection