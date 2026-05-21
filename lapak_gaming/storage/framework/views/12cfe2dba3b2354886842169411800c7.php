<?php $__env->startSection('title', 'Kelola Toko'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-slate-950 py-12 px-4">
    <div class="mx-auto max-w-4xl space-y-8">
        <?php if(session('success')): ?>
            <div class="rounded-3xl border border-emerald-600/30 bg-emerald-500/10 p-4 text-emerald-200"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <div class="rounded-3xl border border-slate-800 bg-slate-900 p-8">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white">Kelola Toko</h1>
                    <p class="mt-2 text-slate-400">Sunting detail toko, dan hapus status seller jika ingin kembali menjadi buyer saja.</p>
                </div>
                <span class="rounded-full bg-amber-500/10 px-4 py-2 text-sm text-amber-200">Seller & Buyer</span>
            </div>

            <form action="<?php echo e(route('seller.store.update')); ?>" method="POST" class="mt-8 space-y-6">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div>
                    <label class="block text-sm font-medium text-slate-300">Nama Toko</label>
                    <input name="store_name" type="text" value="<?php echo e(old('store_name', $user->name)); ?>" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none" required />
                    <?php $__errorArgs = ['store_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-rose-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300">Deskripsi Toko</label>
                    <textarea name="bio" rows="4" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white outline-none"><?php echo e(old('bio', $profile?->bio)); ?></textarea>
                    <?php $__errorArgs = ['bio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-rose-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300">Foto Toko</label>
                    <div class="mt-2 flex items-center gap-4">
                        <div class="w-24 h-24 rounded-lg overflow-hidden bg-black/40 border border-white/5">
                            <?php if(!empty($user->shop_photo)): ?>
                                <img src="<?php echo e(asset($user->shop_photo)); ?>" alt="Foto Toko" class="w-full h-full object-cover">
                            <?php elseif(!empty($profile?->avatar_path)): ?>
                                <img src="<?php echo e(asset($profile->avatar_path)); ?>" alt="Foto Toko" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-sm text-slate-400">No Image</div>
                            <?php endif; ?>
                        </div>

                        <div class="flex-1">
                            <input type="file" name="store_photo" accept="image/*" class="mt-1 block w-full text-sm text-slate-300 file:rounded-lg file:border-0 file:bg-amber-500 file:px-3 file:py-2 file:text-white" />
                            <?php $__errorArgs = ['store_photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-rose-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <button type="submit" class="rounded-2xl bg-amber-500 px-5 py-3 font-semibold text-slate-950 transition hover:bg-amber-400">Simpan Perubahan Toko</button>
            </form>

                <div class="mt-8 rounded-3xl border border-slate-700 bg-slate-950/70 p-6 space-y-4">
                <h2 class="text-xl font-bold text-white">Nonaktifkan / Hapus Toko</h2>
                <p class="mt-2 text-slate-400">Anda dapat menonaktifkan toko agar tidak menerima pesanan lagi, atau menghapus toko secara permanen jika belum ada transaksi.</p>

                <form action="<?php echo e(route('seller.store.deactivate')); ?>" method="POST" class="mt-2 inline-block">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="rounded-2xl bg-amber-500 px-5 py-3 font-semibold text-slate-950 transition hover:bg-amber-400">Nonaktifkan Toko</button>
                </form>

                <form action="<?php echo e(route('seller.store.destroy')); ?>" method="POST" class="mt-2">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="rounded-2xl bg-red-600 px-5 py-3 font-semibold text-white transition hover:bg-red-500">Hapus Toko Permanen</button>
                </form>

                <?php if($errors->has('store')): ?>
                    <p class="mt-2 text-sm text-rose-400"><?php echo e($errors->first('store')); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\0_Project_VS_Code\3. Sistem Basis Data\TB-K1-Database\lapak_gaming\resources\views\seller\store.blade.php ENDPATH**/ ?>