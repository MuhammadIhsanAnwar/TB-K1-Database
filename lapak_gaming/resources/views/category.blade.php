@extends('layouts.app')

@section('title', $category->name ?? 'Kategori')

@section('content')
@php
    $categoryName = data_get($category, 'name', 'Kategori');
@endphp
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Kategori: {{ $categoryName }}</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($products as $product)
            <a href="{{ route('product.show', $product->slug) }}" class="bg-white rounded-lg shadow p-4 block">
                <h2 class="font-semibold">{{ $product->name }}</h2>
                <p class="text-sm text-gray-600 mt-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            </a>
        @empty
            <div class="bg-white rounded-lg shadow p-6 text-gray-600">Belum ada produk di kategori ini.</div>
        @endforelse
    </div>
</div>
@endsection
