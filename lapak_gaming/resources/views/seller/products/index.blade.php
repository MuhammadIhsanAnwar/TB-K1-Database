@extends('layouts.app')

@section('title', 'Kelola Produk')

@section('content')
<div class="min-h-screen bg-slate-950 py-12 px-4">
    <div class="mx-auto max-w-6xl space-y-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">Kelola Produk</h1>
                <p class="mt-2 text-slate-400">Semua produk Anda ditampilkan di sini. Tambah, edit, atau arsipkan produk dengan cepat.</p>
            </div>
            <a href="{{ route('seller.produk.create') }}" class="inline-flex items-center rounded-2xl bg-amber-500 px-5 py-3 font-semibold text-slate-950 hover:bg-amber-400">Tambah Produk</a>
        </div>

        <div class="grid gap-4">
            @forelse($products as $product)
                <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-white">{{ $product->name }}</h2>
                            <p class="mt-2 text-slate-400">{{ $product->category?->name ?? 'Kategori tidak tersedia' }} • {{ $product->status_label }}</p>
                        </div>
                        <span class="text-lg font-bold text-white">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="mt-5 flex flex-wrap gap-3">
                        <a href="{{ route('seller.produk.edit', $product) }}" class="rounded-2xl border border-slate-700 bg-slate-800 px-4 py-2 text-sm text-white">Edit</a>
                        <form action="{{ route('seller.produk.destroy', $product) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-2xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500">Arsipkan</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6 text-slate-400">Belum ada produk. Tambahkan produk baru untuk mulai berjualan.</div>
            @endforelse
        </div>

        <div>{{ $products->links() }}</div>
    </div>
</div>
@endsection
