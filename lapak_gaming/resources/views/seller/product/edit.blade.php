@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Edit Produk</h1>
    <form method="POST" action="{{ route('seller.products.update', $product->id) }}" class="bg-white rounded-lg shadow p-6 space-y-3" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input name="name" value="{{ $product->name }}" class="w-full border rounded p-2" required>
        <input name="price" type="number" value="{{ $product->price }}" class="w-full border rounded p-2" required>
        <input name="stock" type="number" value="{{ $product->stock }}" class="w-full border rounded p-2" required>
        <select name="category_id" class="w-full border rounded p-2" required>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected($product->category_id == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <textarea name="description" class="w-full border rounded p-2">{{ $product->description }}</textarea>
        <button class="px-4 py-2 bg-indigo-600 text-white rounded">Update</button>
    </form>
</div>
@endsection
