<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-950 py-12 px-4">
    <div class="max-w-3xl mx-auto">
        <div class="rounded-3xl border border-slate-800 bg-slate-950 p-8">
            <h1 class="text-3xl font-bold text-white mb-4">Aktifkan Kembali Akun</h1>
            <p class="text-slate-400 mb-8">Login berhasil diverifikasi, tetapi akun ini sedang nonaktif. Konfirmasi dulu untuk mengaktifkan kembali akun sebelum masuk ke website.</p>

            <?php if(session('status')): ?>
                <div class="mb-6 rounded-2xl border border-cyan-500/20 bg-cyan-500/10 p-4 text-cyan-200"><?php echo e(session('status')); ?></div>
            <?php endif; ?>
            <?php if($errors->any()): ?>
                <div class="mb-6 rounded-2xl border border-rose-500/20 bg-rose-500/10 p-4 text-rose-200">
                    <ul class="list-disc list-inside space-y-2 text-sm">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6 mb-8">
                <h2 class="font-semibold text-white mb-3">Informasi Akun</h2>
                <p class="text-slate-300">Email: <span class="text-white"><?php echo e($user->email); ?></span></p>
                <p class="text-slate-300 mt-2">Dinonaktifkan pada: <span class="text-white"><?php echo e($user->deactivated_at->format('d M Y H:i')); ?></span></p>
                <p class="text-slate-300 mt-2">Batas waktu aktivasi: <span class="text-white"><?php echo e($user->deactivated_at->copy()->addMonths(6)->format('d M Y H:i')); ?></span></p>
            </div>

            <form action="<?php echo e(route('account.reactivate')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full rounded-2xl bg-green-600 px-5 py-3 text-sm font-semibold text-white hover:bg-green-500 transition">Aktifkan Kembali Akun</button>
            </form>

            <div class="mt-6 text-sm text-slate-500">
                <p>Jika Anda tidak ingin mengaktifkan akun ini, Anda dapat meninggalkan halaman ini. Akun akan tetap dinonaktifkan.</p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\0_Project_VS_Code\3. Sistem Basis Data\TB-K1-Database\lapak_gaming\resources\views\auth\reactivate-account.blade.php ENDPATH**/ ?>