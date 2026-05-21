<?php $__env->startSection('title', ucfirst($type) . ' — Lapak Geming'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $typeLabels = [
        'topup' => 'Top Up',
        'item' => 'Item',
        'akun' => 'Akun',
        'voucher' => 'Voucher',
        'gamekey' => 'Game Key',
    ];
    $label = $typeLabels[$type] ?? ucfirst($type);
?>

<section class="max-w-7xl mx-auto px-4 py-12">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white"><?php echo e($label); ?></h1>
            <p class="text-sm text-gray-400 mt-2">Menampilkan produk <?php echo e(strtolower($label)); ?> terpopuler.</p>
        </div>
        <a href="<?php echo e(route('products.search')); ?>" class="inline-flex items-center gap-2 rounded-xl border border-gray-800 bg-gray-900 px-5 py-3 text-white hover:border-violet-600 hover:text-violet-100 transition">
            Lihat Semua Produk
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php echo $__env->make('components.product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full rounded-3xl border border-gray-800 bg-gray-900 p-12 text-center text-gray-400">
                Belum ada produk <?php echo e(strtolower($label)); ?> tersedia.
            </div>
        <?php endif; ?>
    </div>

    <div class="mt-8">
        <?php echo e($products->withQueryString()->links()); ?>

    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\0_Project_VS_Code\3. Sistem Basis Data\TB-K1-Database\lapak_gaming\resources\views\products\by-type.blade.php ENDPATH**/ ?>