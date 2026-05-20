@extends('layouts.app')
@section('title', 'Cari Produk — Lapak Geming')

@section('content')
<section class="max-w-7xl mx-auto px-4 py-12">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white">Hasil Pencarian</h1>
            <p class="text-sm text-gray-400 mt-2">Menemukan {{ $products->total() }} produk untuk "{{ $query }}".</p>
        </div>
        <form action="{{ route('products.search') }}" method="GET" class="flex gap-2 w-full md:w-auto">
            <input type="text" name="q" value="{{ $query }}" placeholder="Cari produk..." class="w-full md:w-80 bg-gray-900 border border-gray-800 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500">
    <button type="submit" class="btn-primary px-5 rounded-xl text-white transition-all duration-300 hover:scale-[1.03]">   Cari</button>
        </form>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
    @forelse($products as $index => $product)

        <div class="reveal-card reveal-delay-{{ ($index % 6) + 1 }}">
            @include('components.product-card', ['product' => $product])
        </div>
        @empty
            <div class="col-span-full rounded-3xl border border-gray-800 bg-gray-900 p-12 text-center text-gray-400">
                Tidak ada produk ditemukan. Coba kata kunci lain atau lihat semua produk.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $products->withQueryString()->links() }}
    </div>
</section>
@endsection