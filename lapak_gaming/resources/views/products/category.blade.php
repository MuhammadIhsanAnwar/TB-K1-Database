@extends('layouts.app')
@section('title', $category->name . ' — Lapak Geming')

@section('content')
<section class="max-w-7xl mx-auto px-4 py-12">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white">Kategori: {{ $category->name }}</h1>
            <p class="text-sm text-gray-400 mt-2">Menampilkan produk dalam kategori {{ strtolower($category->name) }}.</p>
        </div>
        <a href="{{ route('products.search') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-800 bg-gray-900 px-5 py-3 text-white hover:border-violet-600 hover:text-violet-100 transition">
            Lihat Semua Produk
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
        @forelse($products as $product)
            @include('components.product-card', ['product' => $product])
        @empty
            <div class="col-span-full rounded-3xl border border-gray-800 bg-gray-900 p-12 text-center text-gray-400">
                Belum ada produk dalam kategori ini.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $products->withQueryString()->links() }}
    </div>
</section>
@endsection