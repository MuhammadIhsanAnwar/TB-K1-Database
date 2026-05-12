@extends('layouts.app')

@section('title', 'Daftar Jadi Seller — Lapak Gaming')

@push('styles')
<style>
  /* ── Photo upload preview area ── */
  .photo-drop {
    position: relative;
    border: 2px dashed #334155;
    border-radius: 16px;
    cursor: pointer;
    transition: border-color .2s, background .2s;
  }
  .photo-drop:hover, .photo-drop.drag-over {
    border-color: #f97316;
    background: rgba(249,115,22,.04);
  }
  .photo-drop input[type=file] {
    position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
  }
  .photo-preview-img {
    width: 100%; height: 180px; object-fit: cover;
    border-radius: 14px; display: none;
  }
  .photo-placeholder {
    padding: 2.5rem 1rem;
    display: flex; flex-direction: column; align-items: center; gap: .75rem;
    color: #64748b;
  }
  .photo-placeholder svg { color: #475569; }

  /* ── Status banners ── */
  .banner-pending  { background: rgba(245,158,11,.08); border: 1px solid rgba(245,158,11,.25); }
  .banner-rejected { background: rgba(239,68,68,.08);  border: 1px solid rgba(239,68,68,.25); }
  .banner-success  { background: rgba(16,185,129,.08); border: 1px solid rgba(16,185,129,.25); }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-slate-950 py-12 px-4">
  <div class="mx-auto max-w-3xl space-y-6">

    {{-- Header --}}
    <div>
      <p class="text-xs uppercase tracking-widest text-amber-400">Seller Registration</p>
      <h1 class="mt-2 text-3xl font-bold text-white">Daftar sebagai Seller</h1>
      <p class="mt-2 text-slate-400">
        Isi data toko Anda dan kirim pengajuan. Admin akan meninjau dan memverifikasi dalam 1–3 hari kerja.
      </p>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
      <div class="banner-success flex items-start gap-3 rounded-2xl p-4">
        <svg class="mt-0.5 w-5 h-5 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm text-emerald-300">{{ session('success') }}</p>
      </div>
    @endif

    @if(session('info'))
      <div class="banner-pending flex items-start gap-3 rounded-2xl p-4">
        <svg class="mt-0.5 w-5 h-5 text-amber-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm text-amber-300">{{ session('info') }}</p>
      </div>
    @endif

    {{-- Pending application notice --}}
    @if(Auth::user()->seller_status === 'pending')
      <div class="banner-pending rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-2">
          <svg class="w-6 h-6 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <h2 class="text-lg font-semibold text-amber-300">Pengajuan Sedang Ditinjau</h2>
        </div>
        <p class="text-sm text-amber-200/80">
          Pengajuan toko <strong>{{ Auth::user()->shop_name }}</strong> Anda sedang dalam proses verifikasi oleh admin.
          Kami akan mengirimkan notifikasi segera setelah pengajuan diproses.
        </p>
      </div>

    {{-- Rejected application notice --}}
    @elseif(Auth::user()->seller_status === 'rejected')
      <div class="banner-rejected rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-2">
          <svg class="w-6 h-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <h2 class="text-lg font-semibold text-red-300">Pengajuan Ditolak</h2>
        </div>
        <p class="text-sm text-red-200/80 mb-1">
          <strong>Alasan:</strong> {{ Auth::user()->seller_rejection_reason ?? 'Tidak ada keterangan.' }}
        </p>
        <p class="text-sm text-slate-400 mt-2">Silakan perbaiki data toko Anda dan ajukan kembali di bawah ini.</p>
      </div>
    @endif

    {{-- Registration form (hide if pending) --}}
    @if(Auth::user()->seller_status !== 'pending')
      <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6 sm:p-8">

        <form method="POST"
              action="{{ route('seller.register') }}"
              enctype="multipart/form-data"
              class="space-y-6"
              id="seller-register-form">
          @csrf

          {{-- Status info --}}
          <div class="rounded-2xl border border-slate-700 bg-slate-950 p-4">
            <p class="text-sm text-slate-400">
              Anda masuk sebagai
              <span class="font-semibold text-white">{{ Auth::user()->name }}</span>
              ({{ Auth::user()->role === 'buyer' ? 'Buyer' : Auth::user()->role }}).
              Akun buyer Anda akan tetap aktif setelah menjadi seller.
            </p>
          </div>

          {{-- Shop Name --}}
          <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">
              Nama Toko <span class="text-red-400">*</span>
            </label>
            <input
              name="shop_name"
              type="text"
              value="{{ old('shop_name', Auth::user()->shop_name) }}"
              class="w-full rounded-2xl border {{ $errors->has('shop_name') ? 'border-red-500/60' : 'border-slate-700' }} bg-slate-950 px-4 py-3 text-white placeholder:text-slate-600 outline-none focus:border-amber-500/60 transition"
              placeholder="Nama toko Anda (contoh: GamingHub Store)"
              required
            />
            @error('shop_name')
              <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
            @enderror
          </div>

          {{-- Shop Photo Upload --}}
          <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">
              Foto Profil Toko <span class="text-red-400">*</span>
            </label>

            <div class="photo-drop" id="photo-drop-zone">
              <input
                type="file"
                name="shop_photo"
                id="shop_photo_input"
                accept="image/jpeg,image/png,image/webp"
                onchange="previewShopPhoto(this)"
                required
              />

              {{-- Placeholder shown before selection --}}
              <div class="photo-placeholder" id="photo-placeholder">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <div class="text-center">
                  <p class="text-sm font-medium text-slate-300">Klik atau seret gambar ke sini</p>
                  <p class="text-xs text-slate-500 mt-1">JPG, JPEG, PNG, WEBP — Maksimal 5 MB</p>
                </div>
              </div>

              {{-- Preview --}}
              <img id="shop-photo-preview" class="photo-preview-img" src="" alt="Preview foto toko" />
            </div>

            {{-- Change button shown after selection --}}
            <button type="button" id="change-photo-btn"
              onclick="document.getElementById('shop_photo_input').click()"
              class="mt-2 hidden text-xs text-amber-400 hover:text-amber-300 transition underline">
              Ganti foto
            </button>

            @error('shop_photo')
              <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
            @enderror
          </div>

          {{-- Shop Description --}}
          <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">
              Deskripsi Toko <span class="text-red-400">*</span>
            </label>
            <textarea
              name="shop_description"
              rows="4"
              maxlength="1000"
              class="w-full rounded-2xl border {{ $errors->has('shop_description') ? 'border-red-500/60' : 'border-slate-700' }} bg-slate-950 px-4 py-3 text-white placeholder:text-slate-600 outline-none focus:border-amber-500/60 transition resize-none"
              placeholder="Ceritakan tentang toko Anda, produk yang dijual, keunggulan toko, dll."
              required
              oninput="updateCharCount(this, 'desc-count', 1000)"
            >{{ old('shop_description', Auth::user()->shop_description) }}</textarea>
            <div class="flex items-center justify-between mt-1">
              @error('shop_description')
                <p class="text-sm text-red-400">{{ $message }}</p>
              @else
                <span></span>
              @enderror
              <span id="desc-count" class="text-xs text-slate-500 ml-auto">
                {{ strlen(old('shop_description', Auth::user()->shop_description ?? '')) }}/1000
              </span>
            </div>
          </div>

          {{-- Validation Errors Global --}}
          @if($errors->any())
            <div class="rounded-2xl border border-red-500/20 bg-red-500/05 p-4">
              <p class="text-sm font-medium text-red-400 mb-1">Mohon perbaiki kesalahan berikut:</p>
              <ul class="text-sm text-red-300 list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          {{-- Submit --}}
          <button
            type="submit"
            id="submit-btn"
            class="w-full rounded-2xl bg-amber-500 px-5 py-3.5 font-semibold text-slate-950 transition hover:bg-amber-400 active:scale-[.99] disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2"
          >
            <span id="submit-text">Kirim Pengajuan Seller</span>
            <svg id="submit-spinner" class="hidden w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
          </button>

          <p class="text-center text-xs text-slate-500">
            Dengan mengajukan, Anda menyetujui
            <a href="{{ route('terms') }}" class="text-slate-400 underline hover:text-white">Syarat Layanan</a>
            Lapak Gaming sebagai seller.
          </p>

        </form>
      </div>
    @endif

    {{-- Info cards --}}
    <div class="grid gap-4 sm:grid-cols-3">
      <div class="rounded-2xl border border-slate-800 bg-slate-900 p-5">
        <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center mb-3">
          <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
          </svg>
        </div>
        <h3 class="text-sm font-semibold text-white mb-1">Verifikasi Aman</h3>
        <p class="text-xs text-slate-500">Setiap pengajuan ditinjau oleh tim admin kami untuk menjaga kualitas marketplace.</p>
      </div>
      <div class="rounded-2xl border border-slate-800 bg-slate-900 p-5">
        <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center mb-3">
          <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <h3 class="text-sm font-semibold text-white mb-1">Proses 1–3 Hari</h3>
        <p class="text-xs text-slate-500">Admin akan memproses pengajuan Anda dalam 1–3 hari kerja setelah data dikirim.</p>
      </div>
      <div class="rounded-2xl border border-slate-800 bg-slate-900 p-5">
        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center mb-3">
          <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
          </svg>
        </div>
        <h3 class="text-sm font-semibold text-white mb-1">Tetap Bisa Beli</h3>
        <p class="text-xs text-slate-500">Akun buyer Anda tetap aktif selama menunggu verifikasi. Tidak ada yang berubah.</p>
      </div>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
  // ── Photo preview ──────────────────────────────────────────────────────────
  function previewShopPhoto(input) {
    const file = input.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
      const preview     = document.getElementById('shop-photo-preview');
      const placeholder = document.getElementById('photo-placeholder');
      const changeBtn   = document.getElementById('change-photo-btn');

      preview.src = e.target.result;
      preview.style.display = 'block';
      placeholder.style.display = 'none';
      changeBtn.classList.remove('hidden');
    };
    reader.readAsDataURL(file);
  }

  // ── Drag-and-drop visual feedback ─────────────────────────────────────────
  const dropZone = document.getElementById('photo-drop-zone');
  if (dropZone) {
    dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('drag-over'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
    dropZone.addEventListener('drop', () => dropZone.classList.remove('drag-over'));
  }

  // ── Character counter ─────────────────────────────────────────────────────
  function updateCharCount(el, counterId, max) {
    const counter = document.getElementById(counterId);
    if (counter) counter.textContent = el.value.length + '/' + max;
  }

  // ── Submit loading state ──────────────────────────────────────────────────
  document.getElementById('seller-register-form')?.addEventListener('submit', function() {
    const btn     = document.getElementById('submit-btn');
    const text    = document.getElementById('submit-text');
    const spinner = document.getElementById('submit-spinner');
    if (btn) btn.disabled = true;
    if (text) text.textContent = 'Mengirim Pengajuan...';
    if (spinner) spinner.classList.remove('hidden');
  });
</script>
@endpush