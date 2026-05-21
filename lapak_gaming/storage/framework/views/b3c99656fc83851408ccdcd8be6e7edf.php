<?php $__env->startSection('title', 'Manajemen Akun — Admin'); ?>

<?php $__env->startPush('styles'); ?>
<style>
  /* ── CSS Hasil "Copy" dari halaman Users ── */
  .tab-btn {
    position: relative;
    padding: .6rem 1.25rem;
    border-radius: 999px;
    font-size: .8125rem;
    font-weight: 600;
    color: #64748b;
    transition: color .2s, background .2s;
    white-space: nowrap;
    cursor: pointer;
    border: none; background: none;
    text-decoration: none !important;
  }
  .tab-btn:hover { color: #cbd5e1; background: rgba(255,255,255,.04); }
  .tab-btn.active { color: #fff; background: rgba(245,158,11,.15); }
  .tab-btn.active::after {
    content: '';
    position: absolute; bottom: -1px; left: 50%; transform: translateX(-50%);
    width: 32px; height: 2px; border-radius: 2px;
    background: #f97316;
  }

  .tab-badge {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 18px; height: 18px; padding: 0 5px; border-radius: 999px;
    font-size: .6875rem; font-weight: 700;
    background: rgba(245,158,11,.2); color: #fbbf24;
    margin-left: 6px;
  }
  .tab-badge.badge-pending { background: rgba(239,68,68,.2); color: #f87171; }

  .pill {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 2px 8px; border-radius: 999px;
    font-size: .6875rem; font-weight: 600;
  }
  .pill-active   { background: rgba(16,185,129,.12); color: #34d399; }
  .pill-pending  { background: rgba(245,158,11,.12); color: #fbbf24; }
  .pill-approved { background: rgba(16,185,129,.12); color: #34d399; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-slate-950 py-10 px-4">
  <div class="mx-auto max-w-7xl space-y-6">

    
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
      <div>
        <p class="text-xs uppercase tracking-widest text-amber-400">Admin Panel</p>
        <h1 class="mt-1 text-3xl font-bold text-white">Manajemen Akun</h1>
        <p class="mt-1 text-slate-400 text-sm">Kelola verifikasi seller dan data buyer platform.</p>
      </div>
      <div class="flex gap-2">
          <a href="<?php echo e(route('admin.dashboard')); ?>"
             class="inline-flex items-center gap-1.5 rounded-2xl border border-slate-700 px-4 py-2.5 text-sm text-slate-300 hover:border-slate-500 hover:text-white transition">
            Dashboard
          </a>
      </div>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="rounded-2xl border border-slate-800 bg-slate-900 p-5">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Total Buyers</p>
            <p class="text-2xl font-bold text-white mt-1"><?php echo e($buyers->total()); ?></p>
        </div>
        <div class="rounded-2xl border border-slate-800 bg-slate-900 p-5">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Verified Sellers</p>
            <p class="text-2xl font-bold text-emerald-400 mt-1"><?php echo e($sellers->total()); ?></p>
        </div>
        <div class="rounded-2xl border border-amber-500/20 bg-amber-500/5 p-5">
            <p class="text-amber-500/50 text-xs font-bold uppercase tracking-wider">Pending Apps</p>
            <p class="text-2xl font-bold text-amber-500 mt-1"><?php echo e($applications->total()); ?></p>
        </div>
    </div>

    
    <div class="overflow-x-auto">
      <div class="inline-flex gap-1 rounded-2xl border border-slate-800 bg-slate-900 p-1.5 min-w-max">
        <a href="?tab=users" class="tab-btn <?php echo e(($tab ?? 'users') === 'users' ? 'active' : ''); ?>">
          Menu User
          <span class="tab-badge"><?php echo e($buyers->total()); ?></span>
        </a>

        <a href="?tab=sellers" class="tab-btn <?php echo e(($tab ?? '') === 'sellers' ? 'active' : ''); ?>">
          Menu Seller
          <span class="tab-badge"><?php echo e($sellers->total()); ?></span>
        </a>

        <a href="?tab=applications" class="tab-btn <?php echo e(($tab ?? '') === 'applications' ? 'active' : ''); ?>">
          Pengajuan Seller
          <?php if($applications->total() > 0): ?>
            <span class="tab-badge badge-pending"><?php echo e($applications->total()); ?></span>
          <?php endif; ?>
        </a>
      </div>
    </div>

    
    <div class="overflow-hidden rounded-3xl border border-slate-800 bg-slate-900">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-800 text-sm text-left text-slate-300">
                <thead class="bg-slate-950 text-xs uppercase tracking-widest text-slate-500">
                    <tr>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Kontak</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    <?php 
                        if(($tab ?? 'users') == 'sellers') {
                            $currentData = $sellers;
                        } elseif(($tab ?? '') == 'applications') {
                            $currentData = $applications;
                        } else {
                            $currentData = $buyers;
                        }
                    ?>

                    <?php $__empty_1 = true; $__currentLoopData = $currentData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-800/40 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-12 w-12 overflow-hidden rounded-2xl border border-slate-700 bg-slate-800">
                                    <img src="<?php echo e($user->shop_photo_url ?? $user->avatar_url); ?>" alt="Avatar <?php echo e($user->name); ?>" class="h-full w-full object-cover" />
                                </div>
                                <div class="min-w-0">
                                    <p class="font-medium text-white"><?php echo e($user->name); ?></p>
                                    <p class="text-xs text-slate-500 font-mono">ID #<?php echo e($user->id); ?></p>
                                    <?php if(($tab ?? '') === 'sellers' || ($tab ?? '') === 'applications'): ?>
                                        <p class="mt-1 text-sm text-slate-400 truncate"><?php echo e($user->shop_name ?? 'Belum ada nama toko'); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-slate-300"><?php echo e($user->email); ?></p>
                            <p class="text-xs text-slate-500"><?php echo e($user->phone ?? '-'); ?></p>
                            <?php if(($tab ?? '') !== 'users' && $user->shop_description): ?>
                                <p class="mt-2 text-xs text-slate-400"><?php echo e(\Illuminate\Support\Str::limit($user->shop_description, 80)); ?></p>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($user->status === 'suspended'): ?>
                                <span class="pill bg-rose-500/10 text-rose-300">Suspended</span>
                                <?php if($user->suspend_reason): ?>
                                    <p class="mt-2 text-xs text-rose-300">Alasan: <?php echo e(\Illuminate\Support\Str::limit($user->suspend_reason, 90)); ?></p>
                                <?php endif; ?>
                            <?php elseif($user->role === 'seller' || $user->seller_status === 'approved'): ?>
                                <span class="pill pill-approved">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Verified Seller
                                </span>
                            <?php elseif($user->seller_status === 'pending'): ?>
                                <span class="pill pill-pending">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span> Pending
                                </span>
                            <?php else: ?>
                                <span class="pill bg-slate-800 text-slate-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span> Buyer
                                </span>
                            <?php endif; ?>
                        </td>
                        
                        
                        <td class="px-6 py-4 text-right">
                            <?php if($user->role !== 'admin'): ?>
                                
                                
                                <?php if(($tab ?? '') === 'applications'): ?>
                                    <div class="flex items-center justify-end gap-2">
                                        <form method="POST" action="<?php echo e(route('admin.users.approve-seller', $user->id)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" onclick="return confirm('Setujui toko <?php echo e($user->shop_name); ?> sebagai seller?')" class="rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-900 px-4 py-2 text-xs font-bold transition shadow-[0_0_10px_rgba(16,185,129,0.3)]">
                                                Approve
                                            </button>
                                        </form>

                                        <form method="POST" action="<?php echo e(route('admin.users.reject-seller', $user->id)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="rejection_reason" value="Foto profil toko atau deskripsi kurang jelas. Silakan perbaiki dan ajukan kembali.">
                                            <button type="submit" onclick="return confirm('Tolak pengajuan toko ini?')" class="rounded-xl bg-red-500/10 border border-red-500/20 px-4 py-2 text-xs font-bold text-red-400 hover:bg-red-500/20 transition">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>

                                
                                <?php else: ?>
                                    <form action="<?php echo e(route('admin.users.status', $user)); ?>" method="POST" class="space-y-2 text-right">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>

                                        <div class="flex items-center justify-end gap-2">
                                            <select name="status" class="rounded-2xl border border-slate-800 bg-slate-950 px-3 py-2 text-sm text-slate-200 outline-none transition focus:border-amber-500">
                                                <option value="active" <?php if($user->status === 'active'): echo 'selected'; endif; ?>>Active</option>
                                                <option value="suspended" <?php if($user->status === 'suspended'): echo 'selected'; endif; ?>>Suspended</option>
                                            </select>
                                            <button type="submit" class="rounded-2xl bg-amber-500 px-4 py-2 text-xs font-bold text-slate-950 hover:bg-amber-400">Simpan</button>
                                        </div>

                                        <textarea name="suspend_reason" rows="2" placeholder="Alasan suspend (opsional)" class="mt-2 w-full rounded-2xl border border-slate-800 bg-slate-950 px-3 py-2 text-sm text-slate-200 outline-none transition focus:border-amber-500"><?php echo e(old('suspend_reason')); ?></textarea>
                                    </form>
                                <?php endif; ?>

                            <?php else: ?>
                                <span class="text-xs text-slate-500">Tidak dapat mengubah admin.</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="py-20 text-center">
                            <p class="text-slate-500 italic">Tidak ada data ditemukan di tab ini.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        
        <div class="px-6 py-4 border-t border-slate-800">
            <?php echo e($currentData->links()); ?>

        </div>
    </div>

  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\0_Project_VS_Code\3. Sistem Basis Data\TB-K1-Database\lapak_gaming\resources\views\admin\accounts\index.blade.php ENDPATH**/ ?>