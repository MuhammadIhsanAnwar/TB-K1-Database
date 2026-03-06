@extends('layouts.app')

@php
    $productName = data_get($product, 'name', 'Detail Produk');
    $productThumb = data_get($product, 'thumbnail');
    $productPrice = (float) data_get($product, 'price', 0);
    $productDesc = data_get($product, 'description', '');
    $productSlug = data_get($product, 'slug');
@endphp

@section('title', $productName)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white rounded-lg shadow p-6">
            @if(!empty($productThumb))
                <img src="{{ $productThumb }}" alt="{{ $productName }}" class="w-full rounded-lg">
            @else
                <div class="h-64 bg-gray-100 rounded-lg flex items-center justify-center text-gray-500">No image</div>
            @endif
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold">{{ $productName }}</h1>
            <p class="text-indigo-600 text-xl font-semibold mt-2">Rp {{ number_format($productPrice, 0, ',', '.') }}</p>
            <p class="text-gray-700 mt-4">{{ $productDesc }}</p>
            @if(!empty($productSlug))
                <a href="{{ route('checkout.confirm', $productSlug) }}" class="inline-block mt-6 px-5 py-2 bg-indigo-600 text-white rounded-lg">Beli Sekarang</a>
            @endif
        </div>
    </div>

    <div class="mt-8">
        <h2 class="text-xl font-bold mb-4">Produk Terkait</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @foreach($relatedProducts as $item)
                <a href="{{ route('product.show', $item->slug) }}" class="bg-white rounded-lg shadow p-4 block">
                    <p class="font-medium">{{ $item->name }}</p>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
