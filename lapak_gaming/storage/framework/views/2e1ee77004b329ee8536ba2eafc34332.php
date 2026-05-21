<?php $__env->startSection('title', 'Verifikasi Email — Lapak Gaming'); ?>

<?php $__env->startSection('content'); ?>
<div class="relative min-h-screen flex items-center justify-center py-16 px-4 overflow-hidden">
  <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 opacity-90"></div>
  <div class="relative w-full max-w-lg">
    <div class="rounded-[2rem] border border-slate-800 bg-slate-950/95 p-8 shadow-2xl shadow-slate-950/30 backdrop-blur-xl">
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-white">Verifikasi Email</h1>
        <p class="mt-3 text-slate-400">Kami telah mengirim email verifikasi ke alamat Anda. Silakan buka email dan klik tautan untuk melanjutkan.</p>
      </div>

      <?php if($errors->any()): ?>
        <div class="mb-6 rounded-2xl border border-rose-500/20 bg-rose-500/10 p-4 text-rose-200">
          <ul class="list-disc list-inside text-sm">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </ul>
        </div>
      <?php endif; ?>
      <?php if(session('status')): ?>
        <div class="mb-6 rounded-2xl border border-cyan-500/20 bg-cyan-500/10 p-4 text-cyan-200"><?php echo e(session('status')); ?></div>
      <?php endif; ?>
      <?php if(session('success')): ?>
        <div class="mb-6 rounded-2xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-emerald-200"><?php echo e(session('success')); ?></div>
      <?php endif; ?>
      <?php if(session('error')): ?>
        <div class="mb-6 rounded-2xl border border-rose-500/20 bg-rose-500/10 p-4 text-rose-200"><?php echo e(session('error')); ?></div>
      <?php endif; ?>
      <?php if(session('warning')): ?>
        <div class="mb-6 rounded-2xl border border-amber-500/20 bg-amber-500/10 p-4 text-amber-200"><?php echo e(session('warning')); ?></div>
      <?php endif; ?>

      <div class="space-y-6">
        <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6 text-sm text-slate-300">
          <p><span class="font-semibold text-white">Alamat email:</span> <?php echo e($email ?? 'Tidak diketahui'); ?></p>
          <p class="mt-3">Jika Anda tidak menemukan email, periksa folder Spam / Junk.</p>
        </div>

        <form action="<?php echo e(route('verification.resend.guest')); ?>" method="POST" class="space-y-4">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="email" value="<?php echo e($email); ?>">
          <button type="submit" class="w-full rounded-2xl bg-slate-800 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-700 transition">Kirim kembali email verifikasi</button>
        </form>

        <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6 text-sm text-slate-400">
          <p class="font-semibold text-white">Sudah memverifikasi email?</p>
          <p class="mt-2">Setelah mengklik tautan verifikasi, Anda dapat masuk menggunakan tombol di bawah.</p>
          <a href="<?php echo e(route('login')); ?>" class="mt-4 inline-flex w-full items-center justify-center rounded-2xl bg-cyan-600 px-5 py-3 text-sm font-semibold text-slate-950 hover:bg-cyan-500 transition">Kembali ke Halaman Login</a>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\0_Project_VS_Code\3. Sistem Basis Data\TB-K1-Database\lapak_gaming\resources\views\auth\verify-pending.blade.php ENDPATH**/ ?>