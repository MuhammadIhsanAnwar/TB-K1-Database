@extends('layouts.app')

@section('title', 'Manage 3D Card - Admin')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<style>
    .img-container img {
        max-width: 100%;
    }
</style>
@endpush

@section('content')
<div class="mx-auto max-w-7xl px-5 py-8">
    <div class="mb-6">
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/5 bg-white/5 hover:bg-white/10 px-4 py-2 text-xs font-bold text-slate-300 transition-all uppercase tracking-widest w-fit">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Dasbor
        </a>
    </div>

    <div class="mb-8">
        <div>
            <h1 class="text-3xl font-black text-white">Manage 3D Card</h1>
            <p class="mt-2 text-sm text-slate-400">Atur konten dinamis untuk 3D Card di halaman utama.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-2xl bg-emerald-500/10 p-4 text-emerald-400 border border-emerald-500/20">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.hero-card.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6 rounded-3xl border border-blue-500/20 bg-[#0B1220]/95 p-8">
        @csrf
        
        <!-- Image Upload & Crop -->
        <div class="border-b border-white/10 pb-6">
            <h2 class="text-xl font-bold text-white mb-4">Gambar Produk</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-400 mb-2">Upload Gambar Baru</label>
                    <input type="file" id="imageInput" accept="image/*" class="w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-500/10 file:text-blue-400 hover:file:bg-blue-500/20"/>
                    <input type="hidden" name="cropped_image" id="cropped_image">
                    
                    @if($heroCard->image_path)
                    <div class="mt-4">
                        <p class="text-xs text-slate-500 mb-2">Gambar Saat Ini:</p>
                        <img src="{{ asset($heroCard->image_path) }}" alt="Current Image" class="rounded-xl border border-white/10 w-64">
                    </div>
                    @endif
                </div>
                <div>
                    <div class="img-container" style="display: none;">
                        <img id="imageToCrop" src="" alt="Picture">
                    </div>
                    <button type="button" id="cropButton" class="mt-4 hidden rounded-xl bg-blue-500 px-4 py-2 text-sm font-bold text-white hover:bg-blue-600 transition">
                        Crop Gambar
                    </button>
                    <div id="croppedPreviewContainer" class="mt-4 hidden">
                        <p class="text-xs text-slate-500 mb-2">Hasil Potongan (Akan disimpan):</p>
                        <img id="croppedPreview" src="" class="rounded-xl border border-emerald-500/50 w-64 shadow-[0_0_15px_rgba(16,185,129,0.2)]">
                    </div>
                </div>
            </div>
        </div>

        <!-- Texts -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
            <div>
                <label class="block text-sm font-semibold text-slate-400 mb-2">Game Title (e.g. Cosmic Warfare)</label>
                <input type="text" name="title" value="{{ old('title', $heroCard->title) }}" required class="w-full rounded-xl border border-white/10 bg-[#050816] px-4 py-3 text-white focus:border-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-400 mb-2">Subtitle (e.g. User ID: 8847291 • Zone: Global)</label>
                <input type="text" name="subtitle" value="{{ old('subtitle', $heroCard->subtitle) }}" required class="w-full rounded-xl border border-white/10 bg-[#050816] px-4 py-3 text-white focus:border-blue-500 outline-none">
            </div>

            <div class="p-4 border border-white/5 rounded-2xl bg-white/5 space-y-4">
                <h3 class="font-bold text-white text-sm">Opsi 1</h3>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Nilai (e.g. 250)</label>
                    <input type="text" name="option1_value" value="{{ old('option1_value', $heroCard->option1_value) }}" required class="w-full rounded-xl border border-white/10 bg-[#050816] px-4 py-2 text-white focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Harga (e.g. Rp 45.000)</label>
                    <input type="text" name="option1_price" value="{{ old('option1_price', $heroCard->option1_price) }}" required class="w-full rounded-xl border border-white/10 bg-[#050816] px-4 py-2 text-white focus:border-blue-500 outline-none">
                </div>
            </div>

            <div class="p-4 border border-white/5 rounded-2xl bg-white/5 space-y-4">
                <h3 class="font-bold text-white text-sm">Opsi 2</h3>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Nilai (e.g. 750)</label>
                    <input type="text" name="option2_value" value="{{ old('option2_value', $heroCard->option2_value) }}" required class="w-full rounded-xl border border-white/10 bg-[#050816] px-4 py-2 text-white focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Harga (e.g. Rp 120.000)</label>
                    <input type="text" name="option2_price" value="{{ old('option2_price', $heroCard->option2_price) }}" required class="w-full rounded-xl border border-white/10 bg-[#050816] px-4 py-2 text-white focus:border-blue-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-400 mb-2">Payment Confirmed Text</label>
                <input type="text" name="payment_text" value="{{ old('payment_text', $heroCard->payment_text) }}" required class="w-full rounded-xl border border-white/10 bg-[#050816] px-4 py-3 text-white focus:border-blue-500 outline-none">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-400 mb-2">Region Server Text</label>
                <input type="text" name="region_text" value="{{ old('region_text', $heroCard->region_text) }}" required class="w-full rounded-xl border border-white/10 bg-[#050816] px-4 py-3 text-white focus:border-blue-500 outline-none">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-400 mb-2">Promo Badge</label>
                <input type="text" name="promo_badge" value="{{ old('promo_badge', $heroCard->promo_badge) }}" required class="w-full rounded-xl border border-white/10 bg-[#050816] px-4 py-3 text-white focus:border-blue-500 outline-none">
            </div>
            
        </div>

        <div class="pt-6 border-t border-white/10 flex justify-end">
            <button type="submit" class="rounded-xl bg-blue-600 px-8 py-3 text-sm font-bold text-white hover:bg-blue-500 transition shadow-[0_0_20px_rgba(37,99,235,0.4)]">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
    let cropper;
    const imageInput = document.getElementById('imageInput');
    const imageToCrop = document.getElementById('imageToCrop');
    const imgContainer = document.querySelector('.img-container');
    const cropButton = document.getElementById('cropButton');
    const croppedPreview = document.getElementById('croppedPreview');
    const croppedPreviewContainer = document.getElementById('croppedPreviewContainer');
    const croppedImageInput = document.getElementById('cropped_image');

    imageInput.addEventListener('change', function (e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            const file = files[0];
            const url = URL.createObjectURL(file);
            imageToCrop.src = url;
            imgContainer.style.display = 'block';
            cropButton.classList.remove('hidden');
            
            if (cropper) {
                cropper.destroy();
            }
            
            // Aspek rasio 280:160 -> 1.75
            cropper = new Cropper(imageToCrop, {
                aspectRatio: 1.75,
                viewMode: 1,
            });
        }
    });

    cropButton.addEventListener('click', function () {
        if (!cropper) return;
        
        const canvas = cropper.getCroppedCanvas({
            width: 560, // 2x resolution for better quality
            height: 320,
        });
        
        const base64Data = canvas.toDataURL('image/jpeg');
        
        croppedPreview.src = base64Data;
        croppedPreviewContainer.classList.remove('hidden');
        croppedImageInput.value = base64Data;
    });
</script>
@endpush
