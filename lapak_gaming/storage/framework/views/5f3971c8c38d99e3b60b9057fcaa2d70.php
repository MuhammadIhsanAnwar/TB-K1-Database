<?php
  $productName = data_get($product, 'name', 'Produk');
  $productCategory = data_get($product, 'category');
  $productSeller = data_get($product, 'seller');
  $productRating = data_get($product, 'rating_average', 0);
  $productReviews = data_get($product, 'reviews', collect());
  $productPrice = data_get($product, 'price', 0);
  $productStock = data_get($product, 'stock', '∞');
?>

<?php $__env->startSection('title', $productName . ' — Lapak Gaming'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 py-10" id="product-page" data-base-price="<?php echo e(data_get($product, 'price', 0)); ?>">

  
  <nav class="flex items-center gap-2 text-xs text-slate-500 mb-8">
    <a href="<?php echo e(route('marketplace.home')); ?>" class="hover:text-slate-300 transition-colors">Beranda</a>
    <span>/</span>
    <?php if($productCategory): ?>
      <a href="<?php echo e(route('categories.show', $productCategory->slug)); ?>" class="hover:text-slate-300 transition-colors"><?php echo e($productCategory->name); ?></a>
    <?php else: ?>
      <span class="text-slate-300">Produk</span>
    <?php endif; ?>
    <span>/</span>
    <span class="text-slate-300 truncate max-w-xs"><?php echo e($productName); ?></span>
  </nav>

  <div class="grid lg:grid-cols-[1fr_380px] gap-8">

    
    <div class="space-y-6">

      
      <div class="card p-6 sm:p-8">
        
        <div class="flex flex-wrap items-center gap-2 mb-4">
          <?php if($productCategory): ?>
            <span class="badge badge-blue"><?php echo e($productCategory->name); ?></span>
          <?php endif; ?>
          <?php if((data_get($product, 'type', '') === 'topup')): ?>
            <span class="badge badge-orange">⚡ Top Up</span>
          <?php endif; ?>
          <?php if($productSeller): ?>
            <span class="badge" style="background:rgba(30,45,69,0.8);color:#94a3b8;border:1px solid #1E2D45;">
              Oleh <?php echo e($productSeller->name); ?>

            </span>
          <?php endif; ?>
        </div>

        <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-white leading-snug mb-4">
          <?php echo e($productName); ?>

        </h1>

        
        <div class="mb-6 rounded-2xl overflow-hidden" style="background:#090E1A;border:1px solid #1E2D45;">
          <?php
            $displayImage = data_get($product, 'image_url') ?? asset('images/default-product.png');
          ?>

          <img src="<?php echo e($displayImage); ?>"
               alt="<?php echo e($productName); ?>"
               class="w-full h-72 sm:h-96 object-cover"
               loading="lazy"
               onerror="this.onerror=null;this.src='<?php echo e(asset('images/default-product.png')); ?>';">
        </div>

        
        <div class="flex flex-wrap gap-5 text-sm text-slate-400 mb-6 pb-6" style="border-bottom:1px solid #1E2D45;">
          <div class="flex items-center gap-1.5">
            <svg class="w-4 h-4 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            <span class="font-semibold text-white"><?php echo e(number_format($productRating, 1)); ?></span>
            <span class="text-slate-500">(<?php echo e(data_get($product, 'review_count', 0)); ?> review)</span>
          </div>
          <div class="flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            <?php echo e(number_format(data_get($product, 'views_count', 0))); ?> dilihat
          </div>
          <div class="flex items-center gap-1.5">
            <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            <span class="text-emerald-400 font-medium"><?php echo e(number_format(data_get($product, 'sold_count', 0))); ?> terjual</span>
          </div>
        </div>

        
        <?php if(data_get($product, 'delivery_content')): ?>
        <div class="rounded-xl p-4 mb-6" style="background:#090E1A;border:1px dashed #1E2D45;">
          <div class="flex items-center gap-2 mb-2">
            <img src="<?php echo e(url('storage/app/public/logo/logo.png')); ?>" alt="Lapak Gaming" class="h-4 w-4 rounded-sm object-contain bg-white/10 p-0.5">
            <span class="text-xs font-display font-semibold text-brand-400 uppercase tracking-wide">Digital Delivery</span>
          </div>
          <p class="text-sm text-slate-300 leading-relaxed"><?php echo e(data_get($product, 'delivery_content')); ?></p>
        </div>
        <?php endif; ?>

        
        <div>
          <h2 class="font-display font-semibold text-white mb-3">Deskripsi Produk</h2>
          <div class="text-sm text-slate-400 leading-relaxed">
            <?php echo e(data_get($product, 'description', 'Tidak ada deskripsi.')); ?>

          </div>
        </div>
      </div>

      
      <div class="card p-6">
        <div class="flex items-center justify-between mb-5">
          <h2 class="font-display font-bold text-white">Ulasan Pembeli</h2>
          <div class="flex items-center gap-2">
            <div class="flex gap-0.5">
              <?php for($i=1;$i<=5;$i++): ?>
                <svg class="w-4 h-4 <?php echo e($i <= round($productRating) ? 'text-amber-400' : 'text-slate-700'); ?>" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
              <?php endfor; ?>
            </div>
            <span class="font-display font-bold text-white"><?php echo e(number_format($productRating, 1)); ?></span>
            <span class="text-sm text-slate-500">(<?php echo e(data_get($product, 'review_count', 0)); ?>)</span>
          </div>
        </div>

        <?php $__empty_1 = true; $__currentLoopData = $productReviews->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="py-4" style="border-bottom:1px solid #1E2D45;">
          <div class="flex items-center gap-3 mb-2">
            <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs"
                 style="background:linear-gradient(135deg,#2563eb,#f97316);">
              <?php echo e(strtoupper(substr($review->user->name ?? 'U', 0, 2))); ?>

            </div>
            <div class="flex-1">
              <div class="flex items-center gap-2">
                <span class="text-sm font-medium text-white"><?php echo e($review->user->name ?? 'Pengguna'); ?></span>
                <div class="flex gap-0.5">
                  <?php for($i=1;$i<=5;$i++): ?>
                    <svg class="w-3 h-3 <?php echo e($i <= $review->rating ? 'text-amber-400' : 'text-slate-700'); ?>" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                  <?php endfor; ?>
                </div>
              </div>
              <span class="text-xs text-slate-500"><?php echo e($review->created_at?->diffForHumans()); ?></span>
            </div>
          </div>
          <p class="text-sm text-slate-300 leading-relaxed pl-11"><?php echo e($review->comment ?? '-'); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="py-8 text-center">
          <div class="text-3xl mb-3">💬</div>
          <p class="text-sm text-slate-400">Belum ada ulasan untuk produk ini.</p>
        </div>
        <?php endif; ?>
      </div>
    </div>

    
    <div class="space-y-4">
      <div class="sticky top-20">

        
        <div class="card-glow-border p-6 mb-4">
          <div class="text-xs font-display font-semibold text-slate-500 uppercase tracking-wider mb-2">Harga Produk</div>
          <div class="font-display text-3xl font-extrabold text-white mb-1">
            Rp <?php echo e(number_format($productPrice, 0, ',', '.')); ?>

          </div>
          <div class="text-xs text-slate-500 mb-5">
            Stok: <span class="text-white font-medium"><?php echo e($productStock); ?></span>
          </div>

          <?php if(auth()->guard()->check()): ?>
            <?php if($errors->any()): ?>
            <div class="mb-4 p-3 bg-red-900/30 border border-red-700 rounded-lg">
              <ul class="list-disc list-inside space-y-1 text-red-200 text-xs">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </ul>
            </div>
            <?php endif; ?>

            <form method="GET" action="<?php echo e(route('checkout.product', $product)); ?>" class="space-y-3">
              
              <div>
                <label class="text-xs text-slate-400 mb-1.5 block">Jumlah</label>
                <div class="flex items-center gap-3">
                  <button type="button" onclick="changeQty(-1)" class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-slate-300 hover:text-white transition-colors" style="background:#162032;border:1px solid #1E2D45;">−</button>
                      <input type="number" id="qty-input" name="quantity" min="1" max="<?php echo e(data_get($product, 'stock', 999)); ?>" value="1"
                         class="input text-center w-16 py-2 text-sm rounded-xl" />
                  <button type="button" onclick="changeQty(1)" class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-slate-300 hover:text-white transition-colors" style="background:#162032;border:1px solid #1E2D45;">+</button>
                </div>
              </div>

              
              <div class="rounded-xl px-4 py-3 flex items-center justify-between" style="background:#090E1A;">
                <span class="text-xs text-slate-400">Total</span>
                <span id="total-price" class="font-display font-bold text-white text-sm">
                  Rp <?php echo e(number_format($productPrice, 0, ',', '.')); ?>

                </span>
              </div>

              <button type="submit" class="btn-primary w-full py-3.5 rounded-xl text-base">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Pesan Sekarang
              </button>

            </form>

            <form method="POST" action="<?php echo e(route('cart.add')); ?>">
              <?php echo csrf_field(); ?>
              <input type="hidden" name="product_id" value="<?php echo e(data_get($product, 'id')); ?>">
              <input type="hidden" name="quantity" value="1">
              <button type="submit" class="btn-ghost w-full py-3 rounded-xl text-sm">
                + Tambah ke Keranjang
              </button>
            </form>
          <?php else: ?>
            <div class="space-y-3">
              <a href="<?php echo e(route('login')); ?>" class="btn-primary w-full py-3.5 rounded-xl text-base text-center">
                Masuk untuk Membeli
              </a>
              <a href="<?php echo e(route('register')); ?>" class="btn-ghost w-full py-3 rounded-xl text-sm text-center">
                Belum punya akun? Daftar →
              </a>
            </div>
          <?php endif; ?>
        </div>

        
        <?php if($productSeller): ?>
        <div class="card p-5">
          <div class="text-xs font-display font-semibold text-slate-500 uppercase tracking-wider mb-3">Penjual</div>
          <div class="flex items-center gap-3 mb-4">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center font-display font-bold"
                 style="background:linear-gradient(135deg,#2563eb,#f97316);">
              <?php echo e(strtoupper(substr($productSeller->name ?? 'S', 0, 2))); ?>

            </div>
            <div>
              <div class="font-semibold text-white text-sm"><?php echo e($productSeller->name); ?></div>
              <div class="text-xs text-slate-400 mt-0.5">
                Level: <span class="text-brand-400"><?php echo e($productSeller->sellerLevel?->name ?? 'Starter'); ?></span>
              </div>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-2 mb-4">
            <?php $__currentLoopData = [
              ['label'=>'Order Rate','value'=>'99.2%'],
              ['label'=>'Respons','value'=>'< 3 mnt'],
              ['label'=>'Bergabung','value'=>$productSeller->created_at?->format('M Y') ?? '-'],
              ['label'=>'Total Produk','value'=>$productSeller->products_count ?? '-'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $si): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="rounded-xl p-3 text-center" style="background:#090E1A;">
              <div class="text-[10px] text-slate-500"><?php echo e($si['label']); ?></div>
              <div class="text-sm font-bold text-white mt-0.5"><?php echo e($si['value']); ?></div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
          <?php if(auth()->guard()->check()): ?>
            <?php if(auth()->id() !== $productSeller->id): ?>
            <a href="<?php echo e(route('chat.product', $product)); ?>"
               class="btn-ghost w-full py-2.5 rounded-xl text-sm text-center">
              💬 Chat Penjual
            </a>
            <?php else: ?>
            <span class="btn-ghost w-full py-2.5 rounded-xl text-sm text-center opacity-60 cursor-not-allowed">
              💬 Ini produk Anda
            </span>
            <?php endif; ?>
          <?php else: ?>
            <a href="<?php echo e(route('login')); ?>" 
            class="btn-ghost w-full py-2.5 rounded-xl text-sm text-center"> 
            💬 Chat Penjual</a>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        
        <div class="card p-4">
          <div class="space-y-3">
            <?php $__currentLoopData = [
              ['icon'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z','label'=>'Dana aman dengan sistem escrow','color'=>'text-emerald-400'],
              ['icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z','label'=>'Garansi uang kembali 7 hari','color'=>'text-brand-400'],
              ['icon'=>'M13 10V3L4 14h7v7l9-11h-7z','label'=>'Proses instan setelah pembayaran','color'=>'text-accent-400'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-center gap-2.5 text-xs text-slate-400">
              <svg class="w-4 h-4 <?php echo e($sb['color']); ?> shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($sb['icon']); ?>"/></svg>
              <?php echo e($sb['label']); ?>

            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  
  <?php if($relatedProducts->isNotEmpty()): ?>
  <div class="mt-14">
    <h2 class="section-title font-display font-bold text-xl text-white mb-6">Produk Serupa</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
      <?php $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php echo $__env->make('components.product-card', ['product' => $related], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  </div>
  <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  const productPage = document.getElementById('product-page');
  const basePrice = Number(productPage?.dataset.basePrice || 0);
  const qtyInput = document.getElementById('qty-input');
  const totalEl = document.getElementById('total-price');

  function changeQty(delta) {
    const max = parseInt(qtyInput.max) || 999;
    let val = parseInt(qtyInput.value) + delta;
    qtyInput.value = Math.max(1, Math.min(val, max));
    updateTotal();
  }

  function updateTotal() {
    const qty = parseInt(qtyInput.value) || 1;
    const total = qty * basePrice;
    totalEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
  }

  if(qtyInput) qtyInput.addEventListener('input', updateTotal);
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\0_Project_VS_Code\3. Sistem Basis Data\TB-K1-Database\lapak_gaming\resources\views\marketplace\product.blade.php ENDPATH**/ ?>