<?php $__env->startSection('title', 'Seller Dashboard Suspended'); ?>

<?php $__env->startSection('content'); ?>
<div class="relative min-h-screen overflow-hidden bg-slate-950 px-4 py-16">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(59,130,246,0.18),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(239,68,68,0.12),transparent_32%)]"></div>
    <div class="relative mx-auto flex max-w-4xl items-center justify-center">
        <div class="w-full rounded-[32px] border border-white/10 bg-white/[0.04] p-8 shadow-[0_30px_120px_rgba(0,0,0,0.45)] backdrop-blur-2xl md:p-10">
            <div class="inline-flex items-center gap-2 rounded-full border border-red-500/30 bg-red-500/10 px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-red-200">
                <span class="h-2 w-2 rounded-full bg-red-400"></span>
                Dashboard Ditangguhkan
            </div>

            <h1 class="mt-6 text-3xl font-black text-white md:text-5xl">
                Akses Seller Sedang Dinonaktifkan
            </h1>

            <p class="mt-4 max-w-2xl text-sm leading-relaxed text-slate-300 md:text-base">
                <?php echo e($suspensionType === 'toko' ? 'Toko seller Anda sedang disuspend oleh admin.' : 'Akun Anda sedang disuspend oleh admin.'); ?>

                Anda tidak dapat mengakses dashboard seller sampai status dipulihkan.
            </p>

            <div class="mt-8 grid gap-4 md:grid-cols-2">
                <div class="rounded-3xl border border-white/10 bg-black/20 p-5">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Informasi Admin</p>
                    <p class="mt-3 text-sm leading-relaxed text-slate-200">
                        <?php echo e($suspensionReason ?: 'Tidak ada catatan tambahan dari admin.'); ?>

                    </p>
                </div>

                <div class="rounded-3xl border border-white/10 bg-black/20 p-5">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Detail Status</p>
                    <div class="mt-3 space-y-2 text-sm text-slate-200">
                        <div>
                            <span class="text-slate-500">Nama:</span>
                            <span class="font-semibold text-white"><?php echo e($user->name); ?></span>
                        </div>
                        <div>
                            <span class="text-slate-500">Status:</span>
                            <span class="font-semibold text-red-300">Disuspend</span>
                        </div>
                        <?php if($suspendedAt): ?>
                            <div>
                                <span class="text-slate-500">Disuspend pada:</span>
                                <span class="font-semibold text-white"><?php echo e($suspendedAt->format('d M Y, H:i')); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <a href="<?php echo e(route('home')); ?>" class="inline-flex items-center justify-center rounded-2xl bg-blue-500 px-5 py-3 text-sm font-bold text-white transition hover:bg-blue-400">
                    Kembali ke Beranda
                </a>
                <a href="<?php echo e(route('settings.profile')); ?>" class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/[0.03] px-5 py-3 text-sm font-bold text-slate-200 transition hover:border-white/20 hover:bg-white/[0.06]">
                    Buka Pengaturan Akun
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\0_Project_VS_Code\3. Sistem Basis Data\TB-K1-Database\lapak_gaming\resources\views\dashboard\seller-suspended.blade.php ENDPATH**/ ?>