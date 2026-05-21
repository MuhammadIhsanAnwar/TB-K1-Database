<?php $__env->startSection('title', 'Daftar Jadi Seller — Lapak Gaming'); ?>

<?php $__env->startPush('styles'); ?>
<style>
  body{
    background:#050816;
  }

  /* ───────── GLOBAL EFFECT ───────── */
  .seller-wrap{
    position:relative;
    overflow:hidden;
  }

  .seller-wrap::before{
    content:'';
    position:fixed;
    top:-180px;
    right:-180px;
    width:420px;
    height:420px;
    background:rgba(59,130,246,.12);
    filter:blur(120px);
    border-radius:9999px;
    pointer-events:none;
    z-index:0;
  }

  .seller-wrap::after{
    content:'';
    position:fixed;
    bottom:-200px;
    left:-160px;
    width:420px;
    height:420px;
    background:rgba(249,115,22,.10);
    filter:blur(120px);
    border-radius:9999px;
    pointer-events:none;
    z-index:0;
  }

  .glass-card{
    position:relative;
    overflow:hidden;
    border:1px solid rgba(255,255,255,.08);
    background:rgba(11,18,32,.88);
    backdrop-filter:blur(18px);
    box-shadow:0 0 50px rgba(37,99,235,.06);
  }

  .glass-card::before{
    content:'';
    position:absolute;
    inset:0;
    background:radial-gradient(circle at top right, rgba(59,130,246,.14), transparent 34%);
    pointer-events:none;
  }

  /* ───────── PHOTO AREA ───────── */
  .photo-drop{
    position:relative;
    border:2px dashed rgba(255,255,255,.10);
    border-radius:24px;
    cursor:pointer;
    overflow:hidden;
    transition:.3s ease;
    background:rgba(255,255,255,.02);
  }

  .photo-drop:hover,
  .photo-drop.drag-over{
    border-color:rgba(249,115,22,.5);
    background:rgba(249,115,22,.05);
    transform:translateY(-2px);
  }

  .photo-drop input[type=file]{
    position:absolute;
    inset:0;
    width:100%;
    height:100%;
    opacity:0;
    cursor:pointer;
    z-index:5;
  }

  .photo-preview-img{
    width:100%;
    height:220px;
    object-fit:cover;
    border-radius:22px;
    display:none;
  }

  .photo-placeholder{
    padding:3rem 1rem;
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:.8rem;
    color:#94a3b8;
  }

  .photo-placeholder svg{
    color:#64748b;
  }

  /* ───────── INPUT ───────── */
  .input-modern{
    width:100%;
    border-radius:20px;
    border:1px solid rgba(255,255,255,.08);
    background:#111827;
    padding:15px 18px;
    color:white;
    outline:none;
    transition:.25s ease;
  }

  .input-modern:focus{
    border-color:rgba(249,115,22,.45);
    box-shadow:0 0 0 4px rgba(249,115,22,.08);
  }

  textarea.input-modern{
    resize:none;
  }

  /* ───────── BANNER ───────── */
  .banner-pending{
    background:rgba(245,158,11,.08);
    border:1px solid rgba(245,158,11,.25);
  }

  .banner-rejected{
    background:rgba(239,68,68,.08);
    border:1px solid rgba(239,68,68,.25);
  }

  .banner-success{
    background:rgba(16,185,129,.08);
    border:1px solid rgba(16,185,129,.25);
  }

  /* ───────── REVEAL ───────── */
  .reveal-up{
    opacity:0;
    transform:translateY(35px);
    animation:revealUp .8s cubic-bezier(.22,1,.36,1) forwards;
  }

  .delay-1{ animation-delay:.12s; }
  .delay-2{ animation-delay:.24s; }
  .delay-3{ animation-delay:.36s; }

  @keyframes revealUp{
    to{
      opacity:1;
      transform:translateY(0);
    }
  }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="seller-wrap min-h-screen px-4 py-14">

  <div class="relative z-10 mx-auto max-w-3xl space-y-6">

    
    <div class="reveal-up glass-card rounded-[34px] px-7 py-8">

      <div class="relative z-10">

        <div
          class="inline-flex items-center gap-2 rounded-full border border-orange-500/30 bg-orange-500/10 px-4 py-2 text-[11px] font-bold tracking-[0.18em] text-orange-300">

          <span class="h-2 w-2 rounded-full bg-orange-400 animate-pulse"></span>
          SELLER REGISTRATION
        </div>

        <h1 class="mt-5 text-4xl font-black text-white">
          Daftar sebagai Seller
        </h1>

        <p class="mt-4 max-w-2xl text-sm leading-relaxed text-slate-400">
          Isi data toko Anda dan kirim pengajuan. Admin akan meninjau dan memverifikasi dalam 1–3 hari kerja.
        </p>

      </div>
    </div>

    
    <?php if(session('success')): ?>
      <div class="reveal-up delay-1 banner-success rounded-3xl p-5">
        <p class="text-sm text-emerald-300"><?php echo e(session('success')); ?></p>
      </div>
    <?php endif; ?>

    <?php if(session('info')): ?>
      <div class="reveal-up delay-1 banner-pending rounded-3xl p-5">
        <p class="text-sm text-amber-300"><?php echo e(session('info')); ?></p>
      </div>
    <?php endif; ?>

    
    <?php if(Auth::user()->seller_status === 'pending'): ?>
      <div class="reveal-up delay-1 banner-pending rounded-[30px] p-6">
        <h2 class="text-xl font-bold text-amber-300">
          Pengajuan Sedang Ditinjau
        </h2>

        <p class="mt-3 text-sm leading-relaxed text-amber-200/80">
          Pengajuan toko <strong><?php echo e(Auth::user()->shop_name); ?></strong> Anda sedang dalam proses verifikasi oleh admin.
        </p>
      </div>

    
    <?php elseif(Auth::user()->seller_status === 'rejected'): ?>
      <div class="reveal-up delay-1 banner-rejected rounded-[30px] p-6">
        <h2 class="text-xl font-bold text-red-300">
          Pengajuan Ditolak
        </h2>

        <p class="mt-3 text-sm text-red-200/80">
          <strong>Alasan:</strong>
          <?php echo e(Auth::user()->seller_rejection_reason ?? 'Tidak ada keterangan.'); ?>

        </p>
      </div>
    <?php endif; ?>

    
    <?php if(Auth::user()->seller_status !== 'pending'): ?>

      <div class="reveal-up delay-2 glass-card rounded-[34px] p-6 sm:p-8">

        <form method="POST"
          action="<?php echo e(route('seller.register')); ?>"
          enctype="multipart/form-data"
          class="relative z-10 space-y-6"
          id="seller-register-form">

          <?php echo csrf_field(); ?>

          
          <div class="rounded-3xl border border-white/5 bg-white/[0.03] p-5">
            <p class="text-sm leading-relaxed text-slate-400">
              Anda masuk sebagai
              <span class="font-semibold text-white"><?php echo e(Auth::user()->name); ?></span>
              (<?php echo e(Auth::user()->role === 'buyer' ? 'Buyer' : Auth::user()->role); ?>).
            </p>
          </div>

          
          <div>
            <label class="mb-2 block text-sm font-semibold text-slate-300">
              Nama Toko
            </label>

            <input
              name="shop_name"
              type="text"
              value="<?php echo e(old('shop_name', Auth::user()->shop_name)); ?>"
              placeholder="GamingHub Store"
              required
              class="input-modern"
            />
          </div>

          
          <div>

            <label class="mb-2 block text-sm font-semibold text-slate-300">
              Foto Profil Toko
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

              <div class="photo-placeholder" id="photo-placeholder">

                <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/>
                </svg>

                <div class="text-center">
                  <p class="text-sm font-semibold text-slate-300">
                    Klik atau seret gambar ke sini
                  </p>

                  <p class="mt-1 text-xs text-slate-500">
                    JPG, PNG, WEBP
                  </p>
                </div>
              </div>

              <img id="shop-photo-preview"
                class="photo-preview-img"
                src=""
                alt="Preview foto toko">
            </div>

            <button type="button"
              id="change-photo-btn"
              onclick="document.getElementById('shop_photo_input').click()"
              class="mt-3 hidden text-xs text-orange-400 underline transition hover:text-orange-300">

              Ganti foto
            </button>

          </div>

          
          <div>

            <label class="mb-2 block text-sm font-semibold text-slate-300">
              Deskripsi Toko
            </label>

            <textarea
              name="shop_description"
              rows="5"
              maxlength="1000"
              required
              oninput="updateCharCount(this, 'desc-count', 1000)"
              class="input-modern"
              placeholder="Ceritakan tentang toko Anda..."><?php echo e(old('shop_description', Auth::user()->shop_description)); ?></textarea>

            <div class="mt-2 flex justify-end">
              <span id="desc-count" class="text-xs text-slate-500">
                <?php echo e(strlen(old('shop_description', Auth::user()->shop_description ?? ''))); ?>/1000
              </span>
            </div>
          </div>

          
          <?php if($errors->any()): ?>
            <div class="rounded-3xl border border-red-500/20 bg-red-500/[0.05] p-5">
              <ul class="space-y-1 text-sm text-red-300">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <li>• <?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </ul>
            </div>
          <?php endif; ?>

          
          <button
            type="submit"
            id="submit-btn"
            class="flex w-full items-center justify-center gap-3 rounded-[22px] bg-gradient-to-r from-orange-500 to-orange-400 px-6 py-4 text-sm font-black text-slate-950 transition duration-300 hover:scale-[1.01] hover:shadow-[0_0_30px_rgba(249,115,22,.30)]">

            <span id="submit-text">
              Kirim Pengajuan Seller
            </span>

            <svg id="submit-spinner"
              class="hidden h-5 w-5 animate-spin"
              fill="none"
              viewBox="0 0 24 24">

              <circle class="opacity-25"
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                stroke-width="4"/>

              <path class="opacity-75"
                fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
          </button>

        </form>
      </div>
    <?php endif; ?>

  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  // ───── REVEAL ON LOAD ─────
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.reveal-up').forEach((el, i) => {
      el.style.animationDelay = `${i * .12}s`;
    });
  });

  // ───── PHOTO PREVIEW ─────
  function previewShopPhoto(input) {

    const file = input.files[0];
    if (!file) return;

    const reader = new FileReader();

    reader.onload = function(e){

      const preview     = document.getElementById('shop-photo-preview');
      const placeholder = document.getElementById('photo-placeholder');
      const changeBtn   = document.getElementById('change-photo-btn');

      preview.src = e.target.result;
      preview.style.display = 'block';

      placeholder.style.display = 'none';

      changeBtn.classList.remove('hidden');
    }

    reader.readAsDataURL(file);
  }

  // ───── DRAG EFFECT ─────
  const dropZone = document.getElementById('photo-drop-zone');

  if(dropZone){

    dropZone.addEventListener('dragover', (e)=>{
      e.preventDefault();
      dropZone.classList.add('drag-over');
    });

    dropZone.addEventListener('dragleave', ()=>{
      dropZone.classList.remove('drag-over');
    });

    dropZone.addEventListener('drop', ()=>{
      dropZone.classList.remove('drag-over');
    });
  }

  // ───── CHARACTER COUNT ─────
  function updateCharCount(el, counterId, max){

    const counter = document.getElementById(counterId);

    if(counter){
      counter.textContent = el.value.length + '/' + max;
    }
  }

  // ───── SUBMIT LOADING ─────
  document.getElementById('seller-register-form')?.addEventListener('submit', function(){

    const btn = document.getElementById('submit-btn');
    const text = document.getElementById('submit-text');
    const spinner = document.getElementById('submit-spinner');

    if(btn){
      btn.disabled = true;
    }

    if(text){
      text.textContent = 'Mengirim Pengajuan...';
    }

    if(spinner){
      spinner.classList.remove('hidden');
    }
  });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\0_Project_VS_Code\3. Sistem Basis Data\TB-K1-Database\lapak_gaming\resources\views\seller\register.blade.php ENDPATH**/ ?>