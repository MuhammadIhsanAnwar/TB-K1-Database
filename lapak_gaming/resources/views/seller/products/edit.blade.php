@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="min-h-screen bg-slate-950 py-12 px-4">
    <div class="mx-auto max-w-4xl rounded-3xl border border-slate-800 bg-slate-900 p-8">
        <h1 class="text-3xl font-bold text-white">Edit Produk</h1>
        <p class="mt-2 text-slate-400">Perbarui detail produk Anda.</p>

        <form action="{{ route('seller.produk.update', $produk) }}" method="POST" enctype="multipart/form-data" class="mt-8 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid gap-4 lg:grid-cols-2">
                <label class="block">
                    <span class="text-sm font-medium text-slate-300">Nama Produk</span>
                    <input name="name" type="text" value="{{ old('name', $produk->name) }}" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none" required />
                </label>
                <label class="block">
                    <span class="text-sm font-medium text-slate-300">Harga</span>
                    <input name="price" type="number" value="{{ old('price', $produk->price) }}" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none" required />
                </label>
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                <label class="block">
                    <span class="text-sm font-medium text-slate-300">Kategori</span>
                    <select name="category_id" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none" required>
                        <option value="">Pilih kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $produk->category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="block">
                    <span class="text-sm font-medium text-slate-300">Jenis Produk</span>
                    <select name="type" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none" required>
                        @foreach(['topup' => 'Topup', 'item' => 'Item', 'akun' => 'Akun', 'voucher' => 'Voucher', 'gamekey' => 'Gamekey'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('type', $produk->type) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                <label class="block">
                    <span class="text-sm font-medium text-slate-300">Stok</span>
                    <input name="stock" type="number" value="{{ old('stock', $produk->stock) }}" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none" required />
                </label>
                <label class="block">
                    <span class="text-sm font-medium text-slate-300">Status</span>
                    <select name="status" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none" required>
                        @foreach(['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('status', $produk->status) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <label class="block">
                <span class="text-sm font-medium text-slate-300">Gambar Produk</span>
                <input name="image" type="file" class="mt-2 w-full text-slate-300" accept="image/*" />
                @if($produk->image)
                    <p class="mt-2 text-sm text-slate-400">Gambar saat ini: {{ $produk->image }}</p>
                @endif
            </label>

            <label class="block">
                <span class="text-sm font-medium text-slate-300">Deskripsi</span>
                <textarea name="description" rows="5" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none">{{ old('description', $produk->description) }}</textarea>
            </label>

            <button type="submit" class="rounded-2xl bg-emerald-500 px-5 py-3 font-semibold text-slate-950 hover:bg-emerald-400">Perbarui Produk</button>
        </form>
    </div>
</div>
@endsection
