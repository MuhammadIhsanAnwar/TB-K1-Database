@extends('layouts.app')

@section('title', 'Produk Seller')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Produk Saya</h1>
        <a href="{{ route('seller.products.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">Tambah Produk</a>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        @forelse($products as $product)
            <div class="py-2 border-b last:border-b-0 flex justify-between">
                <span>{{ $product->name }}</span>
                <a href="{{ route('seller.products.edit', $product->id) }}" class="text-indigo-600">Edit</a>
            </div>
        @empty
            <p class="text-gray-600">Belum ada produk.</p>
        @endforelse
    </div>
</div>
@endsection
