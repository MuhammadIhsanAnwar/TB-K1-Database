<?php $__env->startSection('title', 'Detail Pengguna — Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-slate-950 py-12 px-4">
  <div class="mx-auto max-w-3xl space-y-6">

    
    <div class="flex items-center justify-between gap-4">
      <div>
        <p class="text-xs uppercase tracking-widest text-amber-400">Admin Panel</p>
        <h1 class="mt-1 text-3xl font-bold text-white"><?php echo e($user->name); ?></h1>
        <p class="mt-1 text-slate-400"><?php echo e($user->email); ?></p>
      </div>
      <a href="<?php echo e(route('admin.accounts')); ?>"
         class="inline-flex items-center gap-1.5 rounded-2xl border border-slate-700 px-4 py-2.5 text-sm text-slate-300 hover:border-slate-500 hover:text-white transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
      </a>
    </div>

    
    <?php if(session('success')): ?>
      <div class="flex items-center gap-3 rounded-2xl border border-emerald-500/20 bg-emerald-500/08 p-4">
        <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm text-emerald-300"><?php echo e(session('success')); ?></p>
      </div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
      <div class="flex items-center gap-3 rounded-2xl border border-red-500/20 bg-red-500/08 p-4">
        <p class="text-sm text-red-300"><?php echo e($errors->first()); ?></p>
      </div>
    <?php endif; ?>

    
    <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6">
      <h2 class="text-sm font-semibold text-slate-400 uppercase tracking-widest mb-5">Informasi Pengguna</h2>
      <div class="flex items-center gap-4 mb-6">
        <img src="<?php echo e($user->avatar_url); ?>" alt="<?php echo e($user->name); ?>"
             class="w-16 h-16 rounded-2xl object-cover border border-slate-700" />
        <div>
          <p class="text-xl font-bold text-white"><?php echo e($user->name); ?></p>
          <p class="text-slate-400 text-sm"><?php echo e($user->email); ?></p>
        </div>
      </div>
      <div class="grid grid-cols-2 gap-4 text-sm">
        <div>
          <p class="text-slate-500 text-xs uppercase tracking-wider mb-0.5">Role</p>
          <p class="text-white font-medium capitalize"><?php echo e($user->role); ?></p>
        </div>
        <div>
          <p class="text-slate-500 text-xs uppercase tracking-wider mb-0.5">Bergabung</p>
          <p class="text-white font-medium"><?php echo e($user->created_at->format('d M Y')); ?></p>
        </div>
        <div>
          <p class="text-slate-500 text-xs uppercase tracking-wider mb-0.5">Status Seller</p>
          <p class="text-white font-medium capitalize"><?php echo e($user->seller_status ?? 'none'); ?></p>
        </div>
        <div>
          <p class="text-slate-500 text-xs uppercase tracking-wider mb-0.5">Status Akun</p>
          <span class="inline-flex items-center gap-1.5 text-sm font-semibold <?php echo e($user->status === 'active' ? 'text-emerald-400' : 'text-red-400'); ?>">
            <span class="w-2 h-2 rounded-full <?php echo e($user->status === 'active' ? 'bg-emerald-400' : 'bg-red-400'); ?>"></span>
            <?php echo e($user->status === 'active' ? 'Aktif' : 'Suspended'); ?>

          </span>
        </div>
      </div>

      <?php if($user->status === 'suspended' && $user->suspend_reason): ?>
        <div class="mt-4 rounded-2xl border border-red-500/20 bg-red-500/05 p-3">
          <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Alasan Suspend</p>
          <p class="text-sm text-red-300"><?php echo e($user->suspend_reason); ?></p>
          <?php if($user->suspended_at): ?>
            <p class="text-xs text-slate-600 mt-1">Disuspend: <?php echo e($user->suspended_at->format('d M Y, H:i')); ?></p>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>

    
    <div class="rounded-3xl border border-slate-800 bg-slate-950 p-6">
      <h2 class="text-sm font-semibold text-slate-400 uppercase tracking-widest mb-1">Ubah Status Akun</h2>
      <p class="text-xs text-slate-600 mb-5">
        Admin hanya dapat mengubah status akun. Data pribadi pengguna tidak dapat diubah dari panel admin.
      </p>

      <form action="<?php echo e(route('admin.users.status', $user)); ?>" method="POST" class="space-y-4">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div>
          <label class="block text-sm font-medium text-slate-300 mb-1.5">Status Akun</label>
          <select name="status"
            class="w-full rounded-2xl border border-slate-700 bg-slate-900 px-4 py-3 text-white outline-none focus:border-amber-500/50 transition"
            required
            onchange="toggleSuspendReason(this.value)">
            <option value="active"    <?php if($user->status === 'active'): echo 'selected'; endif; ?>>Aktif</option>
            <option value="suspended" <?php if($user->status === 'suspended'): echo 'selected'; endif; ?>>Suspended</option>
          </select>
        </div>

        <div id="suspend-reason-wrap" class="<?php echo e($user->status === 'suspended' ? '' : 'hidden'); ?>">
          <label class="block text-sm font-medium text-slate-300 mb-1.5">
            Alasan Suspend
            <span class="text-slate-500 font-normal">(akan ditampilkan ke pengguna saat login)</span>
          </label>
          <textarea
            name="suspend_reason"
            rows="3"
            class="w-full rounded-2xl border border-slate-700 bg-slate-900 px-4 py-3 text-white placeholder:text-slate-600 outline-none focus:border-red-500/40 transition resize-none"
            placeholder="Contoh: Melanggar kebijakan marketplace — penjualan item tidak sesuai aturan."
            maxlength="1000"
          ><?php echo e(old('suspend_reason', $user->suspend_reason)); ?></textarea>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 pt-1">
          <button type="submit"
            class="rounded-2xl bg-amber-500 px-6 py-3 text-sm font-semibold text-slate-950 hover:bg-amber-400 transition">
            Simpan Status
          </button>
          <a href="<?php echo e(route('admin.accounts')); ?>"
            class="rounded-2xl border border-slate-700 px-6 py-3 text-sm font-semibold text-slate-300 hover:border-slate-500 transition text-center">
            Batal
          </a>
        </div>
      </form>
    </div>

    
    <?php if($user->shop_name): ?>
      <div class="rounded-3xl border border-slate-800 bg-slate-900 p-6">
        <h2 class="text-sm font-semibold text-slate-400 uppercase tracking-widest mb-4">Data Toko</h2>
        <div class="grid gap-5 sm:grid-cols-2">
          <div>
            <?php if($user->shop_photo): ?>
              <img src="<?php echo e($user->shop_photo_url ?? asset('storage/' . $user->shop_photo)); ?>"
                   alt="Foto toko" class="w-full h-44 object-cover rounded-2xl border border-slate-700 mb-3"
                   onerror="this.src='https://ui-avatars.com/api/?name=<?php echo e(urlencode($user->shop_name)); ?>&size=300&background=1e293b&color=94a3b8'" />
            <?php else: ?>
              <div class="w-full h-44 rounded-2xl border border-slate-700 bg-slate-800 flex items-center justify-center text-slate-500 text-sm mb-3">
                Tidak ada foto
              </div>
            <?php endif; ?>
          </div>
          <div class="space-y-3">
            <div>
              <p class="text-xs text-slate-500 uppercase tracking-wider mb-0.5">Nama Toko</p>
              <p class="text-white font-semibold"><?php echo e($user->shop_name); ?></p>
            </div>
            <div>
              <p class="text-xs text-slate-500 uppercase tracking-wider mb-0.5">Deskripsi</p>
              <p class="text-slate-300 text-sm leading-relaxed"><?php echo e($user->shop_description ?? '—'); ?></p>
            </div>
            <div>
              <p class="text-xs text-slate-500 uppercase tracking-wider mb-0.5">Status Pengajuan</p>
              <span class="inline-flex items-center gap-1.5 text-sm font-semibold
                <?php echo e($user->seller_status === 'approved' ? 'text-emerald-400' :
                   ($user->seller_status === 'pending' ? 'text-amber-400' : 'text-red-400')); ?>">
                <?php echo e(ucfirst($user->seller_status ?? 'none')); ?>

              </span>
            </div>
          </div>
        </div>

        <?php if($user->seller_status === 'suspended'): ?>
          <div class="mt-5 rounded-2xl border border-red-500/20 bg-red-500/05 p-4">
            <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Status Toko</p>
            <p class="text-sm font-semibold text-red-300">Toko sedang disuspend.</p>
            <p class="mt-2 text-sm text-slate-300"><?php echo e($user->suspend_reason ?? 'Tidak ada alasan yang dicatat oleh admin.'); ?></p>
          </div>
        <?php endif; ?>

        <div class="mt-6 grid gap-4 lg:grid-cols-2">
          <div class="rounded-3xl border border-slate-800 bg-slate-950 p-5">
            <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-widest mb-2">Suspend Toko</h3>
            <p class="text-xs text-slate-600 mb-4">Menonaktifkan akses seller dashboard dan aktivitas toko.</p>

            <?php if($user->seller_status !== 'suspended'): ?>
              <form method="POST" action="<?php echo e(route('admin.verification.suspend', $user)); ?>" class="space-y-3">
                <?php echo csrf_field(); ?>
                <textarea name="notes" rows="3"
                  class="w-full rounded-2xl border border-slate-700 bg-slate-900 px-4 py-3 text-white placeholder:text-slate-600 outline-none resize-none focus:border-red-500/40 transition"
                  placeholder="Tulis alasan suspend toko..." required minlength="10" maxlength="2000"></textarea>
                <button type="submit"
                  class="rounded-2xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-500 transition">
                  Suspend Toko
                </button>
              </form>
            <?php else: ?>
              <form method="POST" action="<?php echo e(route('admin.verification.reinstate', $user)); ?>" class="space-y-3">
                <?php echo csrf_field(); ?>
                <textarea name="notes" rows="3"
                  class="w-full rounded-2xl border border-slate-700 bg-slate-900 px-4 py-3 text-white placeholder:text-slate-600 outline-none resize-none focus:border-emerald-500/40 transition"
                  placeholder="Opsional: catatan pemulihan toko..." maxlength="1000"></textarea>
                <button type="submit"
                  class="rounded-2xl bg-emerald-500 px-5 py-2.5 text-sm font-semibold text-slate-950 hover:bg-emerald-400 transition">
                  Pulihkan Toko
                </button>
              </form>
            <?php endif; ?>
          </div>

          <div class="rounded-3xl border border-slate-800 bg-slate-950 p-5">
            <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-widest mb-2">Suspend Akun</h3>
            <p class="text-xs text-slate-600 mb-4">Mencegah user masuk ke akun dan dashboard seller.</p>

            <form action="<?php echo e(route('admin.users.status', $user)); ?>" method="POST" class="space-y-3">
              <?php echo csrf_field(); ?>
              <?php echo method_field('PUT'); ?>
              <input type="hidden" name="status" value="suspended">
              <textarea name="suspend_reason" rows="3"
                class="w-full rounded-2xl border border-slate-700 bg-slate-900 px-4 py-3 text-white placeholder:text-slate-600 outline-none resize-none focus:border-red-500/40 transition"
                placeholder="Tulis alasan suspend akun..." required minlength="10" maxlength="1000"><?php echo e(old('suspend_reason', $user->suspend_reason)); ?></textarea>
              <button type="submit"
                class="rounded-2xl bg-amber-500 px-5 py-2.5 text-sm font-semibold text-slate-950 hover:bg-amber-400 transition">
                Suspend Akun
              </button>
            </form>
          </div>
        </div>

        <?php if($user->seller_status === 'pending'): ?>
          <div class="flex flex-col sm:flex-row gap-3 mt-5 pt-5 border-t border-slate-800">
            <form method="POST" action="<?php echo e(route('admin.users.approve-seller', $user)); ?>">
              <?php echo csrf_field(); ?>
              <button type="submit"
                class="inline-flex items-center gap-2 rounded-2xl bg-emerald-500 px-5 py-2.5 text-sm font-semibold text-slate-950 hover:bg-emerald-400 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                Approve Seller
              </button>
            </form>
            <button onclick="document.getElementById('reject-inline-wrap').classList.toggle('hidden')"
              class="inline-flex items-center gap-2 rounded-2xl border border-red-500/30 bg-red-500/10 px-5 py-2.5 text-sm font-semibold text-red-400 hover:bg-red-500/20 transition">
              Tolak Pengajuan
            </button>
          </div>

          <div id="reject-inline-wrap" class="hidden mt-4">
            <form method="POST" action="<?php echo e(route('admin.users.reject-seller', $user)); ?>" class="space-y-3">
              <?php echo csrf_field(); ?>
              <textarea name="rejection_reason" rows="3"
                class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-white placeholder:text-slate-600 outline-none resize-none focus:border-red-500/40 transition"
                placeholder="Alasan penolakan (minimal 10 karakter)..." required minlength="10" maxlength="1000">
              </textarea>
              <button type="submit"
                class="rounded-2xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-500 transition">
                Kirim Penolakan
              </button>
            </form>
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    
    <?php if($user->role !== 'admin'): ?>
      <div class="rounded-3xl border border-red-900/30 bg-slate-950 p-6">
        <h2 class="text-sm font-semibold text-red-400 uppercase tracking-widest mb-1">Zona Bahaya</h2>
        <p class="text-xs text-slate-500 mb-4">Menghapus akun bersifat permanen dan tidak dapat dibatalkan.</p>
        <form action="<?php echo e(route('admin.users.destroy', $user)); ?>" method="POST"
              onsubmit="return confirm('Hapus permanen akun <?php echo e(addslashes($user->name)); ?>? Tindakan ini tidak bisa dibatalkan.')">
          <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
          <button type="submit"
            class="rounded-2xl border border-red-700 bg-red-700/10 px-5 py-2.5 text-sm font-semibold text-red-400 hover:bg-red-700/20 transition">
            Hapus Akun Permanen
          </button>
        </form>
      </div>
    <?php endif; ?>

  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  function toggleSuspendReason(status) {
    const wrap = document.getElementById('suspend-reason-wrap');
    wrap.classList.toggle('hidden', status !== 'suspended');
  }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\0_Project_VS_Code\3. Sistem Basis Data\TB-K1-Database\lapak_gaming\resources\views\admin\users\show.blade.php ENDPATH**/ ?>