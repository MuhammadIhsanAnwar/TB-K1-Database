<?php $__env->startSection('title', 'Aktivasi Akun'); ?>

<?php $__env->startSection('content'); ?>
    <div class="mx-auto max-w-2xl rounded-4xl border border-slate-200 bg-white p-8 dark:border-slate-800 dark:bg-slate-900">
        <h1 class="text-3xl font-black">Aktivasi Akun</h1>

        <?php if(isset($already) && $already): ?>
            <p class="mt-3 text-sm text-slate-500">Akun Anda telah aktif sebelumnya. Silakan masuk menggunakan akun Anda.</p>
        <?php else: ?>
            <p class="mt-3 text-sm text-slate-500">Terima kasih! Aktivasi berhasil. Sekarang akun Anda aktif dan dapat digunakan untuk masuk.</p>
        <?php endif; ?>

        <div class="mt-6">
            <a href="<?php echo e(route('login')); ?>" class="inline-block rounded-2xl bg-slate-950 px-4 py-3 font-bold text-white dark:bg-white dark:text-slate-950">Masuk ke Akun</a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\0_Project_VS_Code\3. Sistem Basis Data\TB-K1-Database\lapak_gaming\resources\views\auth\activation-success.blade.php ENDPATH**/ ?>