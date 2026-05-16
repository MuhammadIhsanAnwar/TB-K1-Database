@php
use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')

@section('title', 'Kelola Produk')

@section('content')
<div class="min-h-screen bg-slate-950 py-12">
    <div class="mx-auto max-w-6xl space-y-8">
        {{-- Header --}}
        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between px-4">
            <div>
                <h1 class="text-4xl font-black text-white tracking-tighter italic">KELOLA LAPAK</h1>
                <p class="mt-2 text-slate-400 font-medium">Atur semua item jualan kamu dalam satu kendali.</p>
            </div>
            <a href="{{ route('seller.produk.create') }}" class="inline-flex items-center justify-center rounded-2xl bg-amber-500 px-8 py-4 font-black text-slate-950 hover:bg-amber-400 transition-all shadow-lg shadow-amber-500/20">
                + TAMBAH PRODUK BARU
            </a>
        </div>

        <div class="px-4">
            <div class="inline-flex rounded-2xl border border-slate-800 bg-slate-900 p-1">
                <a href="{{ route('seller.produk.index', ['status' => 'active']) }}"
                   class="rounded-xl px-5 py-2 text-sm font-black {{ $status === 'active' ? 'bg-amber-500 text-slate-950' : 'text-slate-400 hover:text-white' }}">
                    Produk Aktif
                </a>
                <a href="{{ route('seller.produk.index', ['status' => 'archived']) }}"
                   class="rounded-xl px-5 py-2 text-sm font-black {{ $status === 'archived' ? 'bg-amber-500 text-slate-950' : 'text-slate-400 hover:text-white' }}">
                    Produk Arsip
                </a>
            </div>
        </div>

        {{-- Product List --}}
        <div class="grid gap-4 px-4">
            @forelse($products as $product)
                <div class="group rounded-[2.5rem] border border-slate-800 bg-slate-900 p-8 transition-all hover:border-amber-500/30">
                    <div class="flex flex-col gap-6 md:flex-row md:items-center">
                        <img src="{{ Storage::url($product->image) }}"
                             class="w-24 h-24 rounded-3xl object-cover border-2 border-slate-800 group-hover:border-amber-500/50 transition-all">
                        
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <h2 class="text-2xl font-black text-white tracking-tight">{{ $product->name }}</h2>
                                <span class="px-3 py-1 bg-slate-800 text-[10px] font-black text-slate-400 rounded-lg uppercase tracking-widest">{{ $product->type }}</span>
                            </div>
                            <p class="mt-2 text-slate-500 font-bold">
                                {{ $product->category?->name ?? 'Tanpa Kategori' }} 
                                <span class="mx-2 text-slate-700">•</span> 
                                <span class="{{ $product->status == 'published' ? 'text-emerald-500' : 'text-rose-500' }} uppercase text-xs font-black">{{ $product->status }}</span>
                            </p>
                        </div>

                        <div class="text-right">
                            <div class="text-sm text-slate-500 font-bold mb-1 uppercase tracking-tighter">Harga Satuan</div>
                            <div class="text-2xl font-black text-white">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-wrap gap-3 border-t border-slate-800 pt-6">
                        <a href="{{ route('seller.produk.edit', $product) }}" class="flex-1 md:flex-none text-center rounded-xl bg-slate-800 px-6 py-3 text-sm font-black text-white hover:bg-slate-700 transition">EDIT DATA</a>

                        @if($status === 'active')
                            <form action="{{ route('seller.produk.destroy', $product) }}" method="POST" class="flex-1 md:flex-none">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full rounded-xl bg-rose-600/10 border border-rose-600/20 px-6 py-3 text-sm font-black text-rose-500 hover:bg-rose-600 hover:text-white transition">ARSIPKAN</button>
                            </form>
                        @else
                            <form action="{{ route('seller.produk.activate', $product) }}" method="POST" class="flex-1 md:flex-none">
                                @csrf
                                <button type="submit" class="w-full rounded-xl bg-emerald-500/10 border border-emerald-500/20 px-6 py-3 text-sm font-black text-emerald-400 hover:bg-emerald-500 hover:text-slate-950 transition">AKTIFKAN KEMBALI</button>
                            </form>

                            <form action="{{ route('seller.produk.forceDestroy', $product) }}" method="POST" class="flex-1 md:flex-none" onsubmit="return confirm('Hapus produk ini permanen?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full rounded-xl bg-rose-600/10 border border-rose-600/20 px-6 py-3 text-sm font-black text-rose-500 hover:bg-rose-600 hover:text-white transition">HAPUS</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="rounded-[2.5rem] border border-dashed border-slate-800 p-20 text-center">
                    <div class="text-slate-600 font-black text-xl italic uppercase">Tidak ada produk di tab ini</div>
                    <p class="text-slate-500 mt-2">Pindah tab atau tambahkan produk baru biar lapak tetap hidup.</p>
                </div>
            @endforelse
        </div>

        <div class="px-4">{{ $products->links() }}</div>
    </div>
</div>
@endsection