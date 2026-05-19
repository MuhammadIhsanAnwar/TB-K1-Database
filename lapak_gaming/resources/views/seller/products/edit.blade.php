@extends('layouts.app')

@section('title', 'Edit Produk')

@push('styles')
<style>
    /* ── True Glassmorphism Design ────────────────────────────── */
    .dashboard-transparent {
        background: transparent !important; /* Biar animasi latar belakang tembus pandang */
    }
    
    .form-card-glass {
        background: rgba(10, 17, 30, 0.35) !important;
        backdrop-filter: blur(24px) saturate(160%);
        border: 1px solid rgba(255, 255, 255, 0.06);
        box-shadow: 0 12px 40px 0 rgba(0, 0, 0, 0.5);
    }

    .input-glass {
        background: rgba(5, 9, 16, 0.45) !important;
        border: 1px solid rgba(255, 255, 255, 0.08);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .input-glass:focus {
        border-color: rgba(245, 158, 11, 0.5) !important;
        box-shadow: 0 0 14px rgba(245, 158, 11, 0.15);
    }

    /* Fix dropdown text color on select option elements */
    .input-glass option {
        background: #0d1421;
        color: #e2e8f0;
    }

    /* Custom File Input Container */
    .file-input-wrapper {
        border: 1px dashed rgba(255, 255, 255, 0.15);
        background: rgba(255, 255, 255, 0.02);
        transition: all 0.2s ease;
    }
    .file-input-wrapper:hover {
        border-color: rgba(245, 158, 11, 0.4);
        background: rgba(245, 158, 11, 0.02);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen py-12 px-4 relative overflow-hidden dashboard-transparent">
    {{-- Ambient Light penambah kontras --}}
    <div class="absolute top-0 left-1/3 w-96 h-96 bg-brand-500/10 rounded-full blur-[150px] pointer-events-none"></div>

    <div class="mx-auto max-w-4xl rounded-3xl p-6 sm:p-8 form-card-glass relative z-10">
        
        {{-- Header Form --}}
        <div class="border-b border-white/5 pb-5">
            <div class="flex items-center gap-2 mb-2">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                <span class="text-xs font-bold uppercase tracking-widest text-amber-500/80">Modify Commodities</span>
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Edit Detail Produk</h1>
            <p class="mt-1 text-slate-400 text-sm font-medium">Perbarui spesifikasi atau sesuaikan harga komoditas lapak jualan Anda.</p>
        </div>

        {{-- Error Validation Alert --}}
        @if ($errors->any())
            <div class="mt-6 rounded-2xl border border-rose-500/30 bg-rose-500/10 p-4 backdrop-blur-md text-rose-300">
                <div class="flex items-center gap-2 mb-1.5 font-bold text-sm">
                    Refusal Alert: Ada beberapa kesalahan data input:
                </div>
                <ul class="list-disc list-inside space-y-1 text-xs font-medium pl-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Start --}}
        <form action="{{ route('seller.produk.update', $produk) }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-5">
            @csrf
            @method('PUT')

            {{-- Row 1: Nama & Harga --}}
            <div class="grid gap-5 lg:grid-cols-2">
                <div class="block">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Nama Produk</span>
                    <input name="name" type="text" value="{{ old('name', $produk->name) }}" class="mt-2 w-full rounded-xl input-glass px-4 py-3 text-sm text-white outline-none" required />
                </div>
                <div class="block">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Harga (Rp)</span>
                    <input name="price" type="number" value="{{ old('price', $produk->price) }}" class="mt-2 w-full rounded-xl input-glass px-4 py-3 text-sm text-white outline-none" required />
                </div>
            </div>

            {{-- Row 2: Kategori & Jenis --}}
            <div class="grid gap-5 lg:grid-cols-2">
                <div class="block">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Kategori Game</span>
                    <select name="category_id" class="mt-2 w-full rounded-xl input-glass px-4 py-3 text-sm text-white outline-none" required>
                        <option value="">Pilih kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $produk->category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="block">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Jenis Layanan Produk</span>
                    <select name="type" class="mt-2 w-full rounded-xl input-glass px-4 py-3 text-sm text-white outline-none" required>
                        @foreach(['topup' => '⚡ Topup Kilat', 'item' => '⚔️ Item & Skin', 'akun' => '👤 Akun Game', 'voucher' => '🎫 Voucher Digital', 'gamekey' => '🔑 Game Key'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('type', $produk->type) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Row 3: Stok & Status Publikasi --}}
            <div class="grid gap-5 lg:grid-cols-2">
                <div class="block">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Jumlah Stok</span>
                    <input name="stock" type="number" value="{{ old('stock', $produk->stock) }}" class="mt-2 w-full rounded-xl input-glass px-4 py-3 text-sm text-white outline-none" required />
                </div>
                <div class="block">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Status Visibilitas Lapak</span>
                    <select name="status" class="mt-2 w-full rounded-xl input-glass px-4 py-3 text-sm text-white outline-none" required>
                        @foreach(['published' => '⚡ Published (Tampilkan)', 'draft' => '💤 Draft (Sembunyikan)', 'archived' => '📁 Archived (Arsip)'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('status', $produk->status) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Row 4: Galeri Foto Manajemen --}}
            <fieldset class="block border-t border-white/5 pt-4">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-3">Manajemen Galeri Foto</span>
                
                {{-- Existing Images Section --}}
                @if($produk->image_paths)
                    <div class="mb-5">
                        <h3 class="text-xs font-semibold text-slate-400 mb-2.5">Foto Saat Ini (Hover & klik tong sampah untuk hapus)</h3>
                        <div class="grid gap-3 grid-cols-2 sm:grid-cols-3 md:grid-cols-4">
                            @foreach($produk->image_paths as $index => $imagePath)
                                <div class="relative rounded-xl overflow-hidden group border border-white/5 bg-black/40 backdrop-blur-sm p-1" data-image-index="{{ $index }}">
                                    <img src="{{ asset('storage/' . $imagePath) }}" 
                                         class="w-full h-24 object-cover rounded-lg" 
                                         alt="Produk {{ $index + 1 }}" />
                                    <button type="button" 
                                            class="remove-image-btn absolute inset-1 bg-black/80 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center"
                                            data-image-index="{{ $index }}">
                                        <svg class="w-5 h-5 text-rose-500 hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                    <div class="absolute bottom-1 left-1 right-1 bg-black/60 px-2 py-1 rounded-b-lg">
                                        <p class="text-[9px] font-mono text-slate-400 truncate">{{ basename($imagePath) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Hidden input to track removed images --}}
                    <input type="hidden" id="removed-images" name="removed_images" value="[]" />
                @endif

                {{-- New Images Upload Section --}}
                <div class="grid gap-4 md:grid-cols-2 items-center">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Tambah Foto Tambahan</span>
                        <div class="relative mt-2 rounded-xl p-3 file-input-wrapper flex items-center justify-center">
                            <input id="product-images" name="images[]" type="file" class="w-full text-xs text-slate-400 cursor-pointer" accept="image/*" multiple />
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 md:mt-6 leading-relaxed">Pilih file gambar jika ingin menambahkan aset foto baru. Batas maksimal 5 MB per berkas foto.</p>
                </div>

                {{-- New Images Preview Section --}}
                <div id="product-image-preview" class="mt-4 grid gap-3 grid-cols-2 sm:grid-cols-3 md:grid-cols-4"></div>
            </fieldset>

            {{-- Row 5: Deskripsi --}}
            <div class="block border-t border-white/5 pt-4">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Deskripsi / Cara Reedem / Informasi Akun</span>
                <textarea name="description" rows="5" class="mt-2 w-full rounded-xl input-glass px-4 py-3 text-sm text-white outline-none resize-none">{{ old('description', $produk->description) }}</textarea>
            </div>

            {{-- Form Actions Footer --}}
            <div class="mt-6 border-t border-white/5 pt-5 flex items-center justify-end gap-3">
                <a href="{{ route('seller.produk.index') }}" class="rounded-xl bg-white/5 hover:bg-white/10 border border-white/5 px-5 py-3 text-xs font-bold text-slate-300 transition-colors tracking-wide">
                    BATALKAN PERUBAHAN
                </a>
                <button type="submit" class="rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 px-6 py-3 text-xs font-bold text-slate-950 transition-all shadow-md shadow-emerald-500/10 hover:scale-[1.01]">
                    PERBARUI DATA LAPAK
                </button>
            </div>
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
        imageDiv.style.opacity = '0.35';
        imageDiv.style.filter = 'grayscale(1)';
        
        // Update hidden input
        document.getElementById('removed-images').value = JSON.stringify(removedImageIndices);
        
        // Add remove indicator
        if (!imageDiv.querySelector('.remove-indicator')) {
            const indicator = document.createElement('div');
            indicator.className = 'remove-indicator absolute top-2 right-2 bg-rose-500 shadow-lg shadow-rose-500/20 rounded-full w-5 h-5 flex items-center justify-center';
            indicator.innerHTML = '<svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>';
            imageDiv.appendChild(indicator);
        }
    });
});

function compressImage(file, maxWidth = 1200, maxHeight = 1200, quality = 0.8) {
    return new Promise((resolve) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = (event) => {
            const img = new Image();
            img.src = event.target.result;
            img.onload = () => {
                let width = img.width;
                let height = img.height;

                if (width > maxWidth || height > maxHeight) {
                    if (width > height) {
                        height = Math.round((height * maxWidth) / width);
                        width = maxWidth;
                    } else {
                        width = Math.round((width * maxHeight) / height);
                        height = maxHeight;
                    }
                }

                const canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;

                const ctx = canvas.getContext('2d');
                
                const mimeType = file.type === 'image/png' || file.type === 'image/webp' ? file.type : 'image/jpeg';
                
                if (mimeType === 'image/jpeg') {
                    ctx.fillStyle = '#FFFFFF';
                    ctx.fillRect(0, 0, width, height);
                }

                ctx.drawImage(img, 0, 0, width, height);
                
                canvas.toBlob((blob) => {
                    if (!blob) {
                        resolve(file);
                        return;
                    }
                    const compressedFile = new File([blob], file.name, {
                        type: mimeType,
                        lastModified: Date.now()
                    });
                    resolve(compressedFile);
                }, mimeType, quality);
            };
            img.onerror = () => resolve(file);
        };
        reader.onerror = () => resolve(file);
    });
}

// New image preview
const productImagePreview = document.getElementById('product-image-preview');
const fileInput = document.getElementById('product-images');
let isCompressing = false;

fileInput?.addEventListener('change', async (e) => {
    if (isCompressing) return;

    const files = Array.from(e.target.files || []);
    if (!files.length) {
        productImagePreview.innerHTML = '';
        return;
    }

    // Show sleek loading state
    productImagePreview.innerHTML = `
        <div class="col-span-full rounded-xl border border-dashed border-amber-500/30 bg-amber-500/5 p-4 text-center text-xs text-amber-400 animate-pulse flex items-center justify-center gap-2">
            <svg class="animate-spin h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Mengompresi foto tambahan...
        </div>
    `;

    isCompressing = true;
    try {
        const compressedFiles = [];
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (file.type.startsWith('image/')) {
                const compressed = await compressImage(file);
                compressedFiles.push(compressed);
            } else {
                compressedFiles.push(file);
            }
        }

        // Update the file input using DataTransfer
        const dt = new DataTransfer();
        compressedFiles.forEach(file => dt.items.add(file));
        fileInput.files = dt.files;

        // Render optimized preview
        productImagePreview.innerHTML = '';
        compressedFiles.forEach((file, idx) => {
            const originalFile = files[idx];
            const originalSizeKb = originalFile ? (originalFile.size / 1024).toFixed(0) : 0;
            const compressedSizeKb = (file.size / 1024).toFixed(0);
            const savedPercent = originalFile ? Math.round(((originalFile.size - file.size) / originalFile.size) * 100) : 0;
            
            const objectUrl = URL.createObjectURL(file);
            const div = document.createElement('div');
            div.className = 'relative rounded-xl overflow-hidden border border-white/5 bg-black/40 backdrop-blur-md p-1.5 group';
            div.innerHTML = `
                <img src="${objectUrl}" class="w-full h-24 object-cover rounded-lg" alt="${file.name}">
                <div class="absolute bottom-1 left-1 right-1 bg-black/60 px-2 py-1 rounded-b-lg">
                    <p class="text-[9px] font-mono text-slate-400 truncate">${file.name}</p>
                </div>
                <div class="absolute top-2 right-2 bg-emerald-500 shadow-md shadow-emerald-500/20 rounded px-1.5 py-0.5 text-[9px] text-white font-bold">
                    ${savedPercent > 0 ? `-${savedPercent}%` : 'OK'}
                </div>
                <div class="absolute top-2 left-2 bg-blue-500 shadow-md shadow-blue-500/20 rounded px-1.5 py-0.5 text-[9px] text-white font-bold uppercase tracking-wider">
                    Baru
                </div>
            `;
            productImagePreview.appendChild(div);
        });
    } catch (err) {
        console.error("Gagal mengompresi gambar tambahan:", err);
        // Fallback to original preview
        productImagePreview.innerHTML = '';
        files.forEach((file) => {
            const reader = new FileReader();
            reader.onload = (event) => {
                const div = document.createElement('div');
                div.className = 'relative rounded-xl overflow-hidden border border-white/5 bg-black/40 backdrop-blur-md p-1.5 group';
                div.innerHTML = `
                    <img src="${event.target.result}" class="w-full h-24 object-cover rounded-lg" alt="${file.name}">
                    <div class="absolute bottom-1 left-1 right-1 bg-black/60 px-2 py-1 rounded-b-lg">
                        <p class="text-[9px] font-mono text-slate-400 truncate">${file.name}</p>
                    </div>
                    <div class="absolute top-2 right-2 bg-blue-500 shadow-md shadow-blue-500/20 rounded px-1.5 py-0.5 text-[9px] text-white font-bold uppercase tracking-wider">
                        Baru
                    </div>
                `;
                productImagePreview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    } finally {
        isCompressing = false;
    }
});
</script>
@endsection