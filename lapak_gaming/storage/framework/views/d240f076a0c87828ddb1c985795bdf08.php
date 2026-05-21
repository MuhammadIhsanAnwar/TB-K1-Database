<?php $__env->startSection('title', 'Cari Produk — Lapak Geming'); ?>

<?php $__env->startSection('content'); ?>
<section class="max-w-7xl mx-auto px-4 py-12">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white">Hasil Pencarian</h1>
            <p class="text-sm text-gray-400 mt-2">Menemukan <?php echo e($products->total()); ?> produk untuk "<?php echo e($query); ?>".</p>
        </div>
        <form action="<?php echo e(route('products.search')); ?>" method="GET" class="flex gap-2 w-full md:w-auto">
            <input type="text" name="q" value="<?php echo e($query); ?>" placeholder="Cari produk..." class="w-full md:w-80 bg-gray-900 border border-gray-800 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500">
    <button type="submit" class="btn-primary px-5 rounded-xl text-white transition-all duration-300 hover:scale-[1.03]">   Cari</button>
        </form>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

        <div class="reveal-card reveal-delay-<?php echo e(($index % 6) + 1); ?>">
            <?php echo $__env->make('components.product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full rounded-3xl border border-gray-800 bg-gray-900 p-12 text-center text-gray-400">
                Tidak ada produk ditemukan. Coba kata kunci lain atau lihat semua produk.
            </div>
        <?php endif; ?>
    </div>

    <div class="mt-8">
        <?php echo e($products->withQueryString()->links()); ?>

    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\0_Project_VS_Code\3. Sistem Basis Data\TB-K1-Database\lapak_gaming\resources\views\products\search.blade.php ENDPATH**/ ?>