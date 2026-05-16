@extends('layouts.app')

@section('title', 'Chat Order')

@section('content')
<div class="min-h-screen bg-slate-950 text-white p-10">
    <h1 class="text-3xl font-bold mb-4">
        Chat Order #{{ $order->invoice_number }}
    </h1>

    <div class="bg-slate-900 p-6 rounded-3xl">
        <p>Buyer: {{ $order->buyer->name ?? '-' }}</p>
        <p>Seller: {{ $order->seller->name ?? '-' }}</p>

        <div class="mt-6">
            <textarea
                class="w-full rounded-2xl bg-slate-800 border border-slate-700 p-4"
                rows="5"
                placeholder="Tulis pesan..."
            ></textarea>

            <button class="mt-4 bg-sky-500 px-5 py-3 rounded-2xl">
                Kirim Pesan
            </button>
        </div>
    </div>
</div>
@endsection