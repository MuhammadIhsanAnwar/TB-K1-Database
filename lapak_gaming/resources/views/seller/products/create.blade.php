@extends('layouts.app')

@section('title', 'Tambah Produk')

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

    /* Fix teks opsi select dropdown agar tidak ikut transparan di OS tertentu */
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
    <div class="absolute top-0 right-1/4 w-96 h-96 bg-brand-500/10 rounded-full blur-[150px] pointer-events-none"></div>

    <div class="mx-auto max-w-4xl rounded-3xl p-6 sm:p-8 form-card-glass relative z-10">
        
        {{-- Header Form --}}
        <div class="border-b border-white/5 pb-5">
            <div class="flex items-center gap-2 mb-2">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                <span class="text-xs font-bold uppercase tracking-widest text-amber-500/80">Add Commodities</span>
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Tambah Produk Baru</h1>
            <p class="mt-1 text-slate-400 text-sm font-medium">Lengkapi detail spesifikasi produk untuk ditampilkan ke etalase toko Anda.</p>
        </div>

        {{-- Error Validation Alert --}}
        @if ($errors->any())
            <div class="mt-6 rounded-2xl border border-rose-500/30 bg-rose-500/10 p-4 backdrop-blur-md text-rose-300">
                <div class="flex items-center gap-2 mb-1.5 font-bold text-sm">
                    ⚠️ Ada beberapa kesalahan input:
                </div>
                <ul class="list-disc list-inside space-y-1 text-xs font-medium pl-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Start --}}
        <form action="{{ route('seller.produk.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-5">
            @csrf

            {{-- Row 1: Nama & Harga --}}
            <div class="grid gap-5 lg:grid-cols-2">
                <div class="block">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Nama Produk</span>
                    <input name="name" type="text" value="{{ old('name') }}" placeholder="Contoh: 1000 Diamonds MLBB" class="mt-2 w-full rounded-xl input-glass px-4 py-3 text-sm text-white outline-none" required />
                </div>
                <div class="block">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Harga Jual (Rp)</span>
                    <input name="price" type="number" value="{{ old('price') }}" placeholder="Masukkan nominal angka" class="mt-2 w-full rounded-xl input-glass px-4 py-3 text-sm text-white outline-none" required />
                </div>
            </div>

            {{-- Row 2: Kategori & Jenis --}}
            <div class="grid gap-5 lg:grid-cols-2">
                <div class="block">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Kategori Game</span>
                    <select name="category_id" class="mt-2 w-full rounded-xl input-glass px-4 py-3 text-sm text-white outline-none" required>
                        <option value="">Pilih kategori game</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="block">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Jenis Layanan Produk</span>
                    <select name="type" class="mt-2 w-full rounded-xl input-glass px-4 py-3 text-sm text-white outline-none" required>
                        <option value="">Pilih tipe item</option>
                        @foreach(['topup' => '⚡ Topup Kilat', 'item' => '⚔️ Item & Skin', 'akun' => '👤 Akun Game', 'voucher' => '🎫 Voucher Digital', 'gamekey' => '🔑 Game Key / Gift Card'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('type') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Row 3: Stok & Upload Gambar --}}
            <div class="grid gap-5 lg:grid-cols-2">
                <div class="block">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Jumlah Stok Barang</span>
                    <input name="stock" type="number" value="{{ old('stock', 0) }}" class="mt-2 w-full rounded-xl input-glass px-4 py-3 text-sm text-white outline-none" required />
                </div>
                <div class="block">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Unggah Banner / Foto Produk</span>
                    <div class="relative mt-2 rounded-xl p-3 file-input-wrapper flex items-center justify-center">
                        <input id="product-images" name="images[]" type="file" class="w-full text-xs text-slate-400 cursor-pointer" accept="image/*" multiple />
                    </div>
                    <p class="mt-1.5 text-[11px] text-slate-500 font-medium">Maksimal file size 5 MB per foto. Kamu bisa memilih multi-file sekaligus.</p>
                </div>
            </div>

            {{-- Image Preview Section --}}
            <div class="border-t border-white/5 pt-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Live Preview Foto</span>
                    <span class="text-[11px] text-slate-500 font-medium">Akan tampil di etalase pembeli</span>
                </div>
                <div id="product-image-preview" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3"></div>
            </div>

            {{-- Row 4: Deskripsi --}}
            <div class="block">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Deskripsi Ringkas / Ketentuan Produk</span>
                <textarea name="description" rows="5" placeholder="Tuliskan detail cara pembelian atau informasi akun secara rinci di sini..." class="mt-2 w-full rounded-xl input-glass px-4 py-3 text-sm text-white outline-none resize-none">{{ old('description') }}</textarea>
            </div>

            {{-- Form Actions Footer --}}
            <div class="mt-6 border-t border-white/5 pt-5 flex items-center justify-end gap-3">
                <a href="{{ route('seller.produk.index') }}" class="rounded-xl bg-white/5 hover:bg-white/10 border border-white/5 px-5 py-3 text-xs font-bold text-slate-300 transition-colors tracking-wide">
                    BATALKAN
                </a>
                <button type="submit" class="rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 px-6 py-3 text-xs font-bold text-slate-950 transition-all shadow-md shadow-emerald-500/10 hover:scale-[1.01]">
                    SIMPAN DAN PUBLIKASIKAN
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const productImagesInput = document.getElementById('product-images');
    const productImagePreview = document.getElementById('product-image-preview');

    if (productImagesInput && productImagePreview) {
        productImagesInput.addEventListener('change', () => {
            const files = Array.from(productImagesInput.files || []);

            if (!files.length) {
                productImagePreview.innerHTML = '<div class="col-span-full rounded-xl border border-dashed border-slate-800/80 p-5 text-center text-xs text-slate-500">Belum ada foto yang dipilih.</div>';
                return;
            }

            productImagePreview.innerHTML = files.map((file) => {
                const objectUrl = URL.createObjectURL(file);
                return `
                    <div class="overflow-hidden rounded-xl border border-white/5 bg-black/40 backdrop-blur-md p-1.5 group">
                        <img src="${objectUrl}" alt="Pratinjau foto produk" class="h-32 w-full object-cover rounded-lg">
                        <div class="px-1 py-1.5 text-[10px] text-slate-400 truncate font-mono">${file.name}</div>
                    </div>
                `;
            }).join('');
        });

        productImagePreview.innerHTML = '<div class="col-span-full rounded-xl border border-dashed border-slate-800/80 p-5 text-center text-xs text-slate-500">Belum ada foto yang dipilih.</div>';
    }
</script>
@endsection