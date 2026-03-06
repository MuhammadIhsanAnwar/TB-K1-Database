@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Tambah Produk</h1>
    <form method="POST" action="{{ route('seller.products.store') }}" class="bg-white rounded-lg shadow p-6 space-y-3" enctype="multipart/form-data">
        @csrf
        <input name="name" class="w-full border rounded p-2" placeholder="Nama produk" required>
        <input name="price" type="number" class="w-full border rounded p-2" placeholder="Harga" required>
        <input name="stock" type="number" class="w-full border rounded p-2" placeholder="Stok" required>
        <select name="category_id" class="w-full border rounded p-2" required>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        <textarea name="description" class="w-full border rounded p-2" placeholder="Deskripsi"></textarea>
        <button class="px-4 py-2 bg-indigo-600 text-white rounded">Simpan</button>
    </form>
</div>
@endsection
