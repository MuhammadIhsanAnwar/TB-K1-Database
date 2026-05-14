@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="min-h-screen bg-slate-950 py-12 px-4">
    <div class="mx-auto max-w-4xl rounded-3xl border border-slate-800 bg-slate-900 p-8">
        <h1 class="text-3xl font-bold text-white">Edit Produk</h1>
        <p class="mt-2 text-slate-400">Perbarui detail produk Anda.</p>

        @if ($errors->any())
            <div class="mt-6 rounded-2xl border border-rose-500/20 bg-rose-500/10 p-4 text-rose-200">
                <ul class="list-disc list-inside space-y-1 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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

            <fieldset class="block">
                <span class="text-sm font-medium text-slate-300 block mb-4">Foto Produk</span>
                
                {{-- Existing Images Section --}}
                @if($produk->image_paths)
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-slate-300 mb-3">Foto Saat Ini</h3>
                        <div class="grid gap-3 grid-cols-2 sm:grid-cols-3 md:grid-cols-4">
                            @foreach($produk->image_paths as $index => $imagePath)
                                <div class="relative rounded-xl overflow-hidden group" data-image-index="{{ $index }}">
                                    <img src="{{ asset('storage/' . $imagePath) }}" 
                                         class="w-full h-24 object-cover border border-slate-700" 
                                         alt="Produk {{ $index + 1 }}" />
                                    <button type="button" 
                                            class="remove-image-btn absolute inset-0 bg-black/70 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center"
                                            data-image-index="{{ $index }}">
                                        <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/50 to-transparent px-2 py-1">
                                        <p class="text-xs text-slate-300 truncate">{{ basename($imagePath) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p class="mt-2 text-xs text-slate-500">Arahkan ke gambar untuk menghapus</p>
                    </div>

                    {{-- Hidden input to track removed images --}}
                    <input type="hidden" id="removed-images" name="removed_images" value="[]" />
                @endif

                {{-- New Images Upload Section --}}
                <div class="mb-4">
                    <label class="block mb-2">
                        <span class="text-sm text-slate-400">Tambah Foto Baru</span>
                        <input id="product-images" name="images[]" type="file" class="mt-2 w-full text-slate-300" accept="image/*" multiple />
                    </label>
                    <p class="text-xs text-slate-500">Maksimal 5 MB per foto, bisa pilih lebih dari satu file.</p>
                </div>

                {{-- New Images Preview Section --}}
                <div id="product-image-preview" class="mt-3 grid gap-3 grid-cols-2 sm:grid-cols-3 md:grid-cols-4"></div>
            </fieldset>

            <label class="block">
                <span class="text-sm font-medium text-slate-300">Deskripsi</span>
                <textarea name="description" rows="5" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none">{{ old('description', $produk->description) }}</textarea>
            </label>

            <button type="submit" class="rounded-2xl bg-emerald-500 px-5 py-3 font-semibold text-slate-950 hover:bg-emerald-400">Perbarui Produk</button>
        </form>
    </div>
</div>

<script>
let removedImageIndices = [];

// Handle existing image removal buttons
document.querySelectorAll('.remove-image-btn').forEach(btn => {
    btn.addEventListener('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        const index = parseInt(this.dataset.imageIndex);
        
        if (!removedImageIndices.includes(index)) {
            removedImageIndices.push(index);
        }
        
        const imageDiv = this.closest('[data-image-index]');
        imageDiv.style.opacity = '0.5';
        imageDiv.style.filter = 'grayscale(1)';
        
        // Update hidden input
        document.getElementById('removed-images').value = JSON.stringify(removedImageIndices);
        
        // Add remove indicator
        if (!imageDiv.querySelector('.remove-indicator')) {
            const indicator = document.createElement('div');
            indicator.className = 'remove-indicator absolute top-1 right-1 bg-red-500 rounded-full w-5 h-5 flex items-center justify-center';
            indicator.innerHTML = '<svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>';
            imageDiv.appendChild(indicator);
        }
    });
});

// New image preview
const productImagePreview = document.getElementById('product-image-preview');
const fileInput = document.getElementById('product-images');

fileInput?.addEventListener('change', (e) => {
    productImagePreview.innerHTML = '';
    Array.from(e.target.files).forEach((file, fileIndex) => {
        const reader = new FileReader();
        reader.onload = (event) => {
            const div = document.createElement('div');
            div.className = 'relative rounded-xl overflow-hidden group';
            div.innerHTML = `
                <img src="${event.target.result}" class="w-full h-24 object-cover border border-slate-600 bg-slate-800" alt="${file.name}">
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/50 to-transparent px-2 py-1">
                    <p class="text-xs text-slate-300 truncate">${file.name}</p>
                </div>
                <div class="absolute top-1 right-1 bg-blue-500 rounded-full px-2 py-1 text-xs text-white font-semibold">
                    Baru
                </div>
            `;
            productImagePreview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endsection
