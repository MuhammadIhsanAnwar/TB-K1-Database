<?php $__env->startSection('title', 'Verifikasi Seller — Admin'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.vcard {
    background: rgba(13,20,33,.9);
    border: 1px solid rgba(30,45,69,.8);
    border-radius: 16px;
    transition: border-color .2s, box-shadow .2s;
}
.vcard:hover { border-color: rgba(59,130,246,.35); box-shadow: 0 0 20px rgba(59,130,246,.08); }
.status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px; border-radius: 999px; font-size: .7rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase;
}
.s-pending      { background: rgba(245,158,11,.12); color: #fbbf24; border: 1px solid rgba(245,158,11,.2); }
.s-under_review { background: rgba(99,102,241,.12); color: #a5b4fc; border: 1px solid rgba(99,102,241,.2); }
.s-need_revision{ background: rgba(249,115,22,.12); color: #fb923c; border: 1px solid rgba(249,115,22,.2); }
.s-approved     { background: rgba(16,185,129,.12); color: #34d399; border: 1px solid rgba(16,185,129,.2); }
.s-rejected     { background: rgba(239,68,68,.12);  color: #f87171; border: 1px solid rgba(239,68,68,.2); }
.s-suspended    { background: rgba(100,116,139,.12); color: #94a3b8; border: 1px solid rgba(100,116,139,.2); }
.tab-item { padding: .5rem 1.1rem; border-radius: 999px; font-size: .82rem; font-weight: 600; cursor: pointer; transition: background .15s, color .15s; color: #64748b; white-space: nowrap; }
.tab-item:hover { color: #e2e8f0; background: rgba(255,255,255,.05); }
.tab-item.active { color: #fff; background: rgba(245,158,11,.18); }
.count-dot { display: inline-flex; align-items: center; justify-content: center; min-width: 18px; height: 18px; border-radius: 999px; font-size: .65rem; font-weight: 700; margin-left: 4px; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen py-8 px-4">
<div class="mx-auto max-w-7xl space-y-6">

    
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs uppercase tracking-widest text-amber-400 font-bold mb-1">Admin Panel</p>
            <h1 class="text-3xl font-black text-white">Verifikasi Seller</h1>
            <p class="text-slate-400 text-sm mt-1">Kelola pengajuan, klarifikasi, dan status verifikasi penjual.</p>
        </div>
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="inline-flex items-center gap-2 text-sm text-slate-400 hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Dashboard
        </a>
    </div>

    
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
        <?php $__currentLoopData = [
            ['pending', 'Pending', '#fbbf24', 'rgba(245,158,11,.1)', '⏳'],
            ['under_review', 'Direview', '#a5b4fc', 'rgba(99,102,241,.1)', '🔍'],
            ['need_revision', 'Perlu Revisi', '#fb923c', 'rgba(249,115,22,.1)', '✏️'],
            ['approved', 'Disetujui', '#34d399', 'rgba(16,185,129,.1)', '✅'],
            ['rejected', 'Ditolak/Suspend', '#f87171', 'rgba(239,68,68,.1)', '❌'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$key, $label, $color, $bg, $icon]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="?tab=<?php echo e($key); ?>" class="vcard p-4 <?php echo e($tab === $key ? 'border-amber-500/40' : ''); ?>">
            <div class="text-xl mb-2"><?php echo e($icon); ?></div>
            <div class="text-2xl font-black text-white"><?php echo e(number_format($counts[$key])); ?></div>
            <div class="text-xs text-slate-400 mt-1"><?php echo e($label); ?></div>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="vcard p-1 flex gap-1 overflow-x-auto">
        <?php $__currentLoopData = [
            ['pending', '⏳ Pending', 'bg-amber-500/20 text-amber-300'],
            ['under_review', '🔍 Direview', 'bg-indigo-500/20 text-indigo-300'],
            ['need_revision', '✏️ Perlu Revisi', 'bg-orange-500/20 text-orange-300'],
            ['approved', '✅ Disetujui', 'bg-emerald-500/20 text-emerald-300'],
            ['rejected', '❌ Ditolak/Suspend', 'bg-red-500/20 text-red-300'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$key, $label, $cls]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="?tab=<?php echo e($key); ?>" class="tab-item <?php echo e($tab === $key ? 'active' : ''); ?>">
            <?php echo e($label); ?>

            <?php if($counts[$key] > 0): ?>
            <span class="count-dot <?php echo e($tab === $key ? 'bg-amber-500/30 text-amber-200' : 'bg-slate-700 text-slate-300'); ?>">
                <?php echo e($counts[$key]); ?>

            </span>
            <?php endif; ?>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <?php if($users->isEmpty()): ?>
    <div class="vcard p-16 text-center">
        <div class="text-5xl mb-4">🎉</div>
        <h3 class="text-white font-bold text-lg">Tidak ada data</h3>
        <p class="text-slate-400 text-sm mt-1">Belum ada pengajuan dengan status ini.</p>
    </div>
    <?php else: ?>
    <div class="space-y-3">
        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="vcard p-5 hover:cursor-pointer" onclick="window.location='<?php echo e(route('admin.verification.show', $user)); ?>'">
            <div class="flex items-start gap-4">
                
                <img src="<?php echo e($user->shop_photo ? asset('storage/' . $user->shop_photo) : $user->avatar_url); ?>"
                     alt="<?php echo e($user->name); ?>"
                     class="w-14 h-14 rounded-2xl object-cover shrink-0 border border-slate-700">

                
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <h3 class="font-bold text-white text-base truncate"><?php echo e($user->name); ?></h3>
                        <span class="status-badge s-<?php echo e($user->seller_status); ?>">
                            <?php echo e(match($user->seller_status) {
                                'pending'       => 'Pending',
                                'under_review'  => 'Direview',
                                'need_revision' => 'Perlu Revisi',
                                'approved'      => 'Disetujui',
                                'rejected'      => 'Ditolak',
                                'suspended'     => 'Suspend',
                                default         => ucfirst($user->seller_status),
                            }); ?>

                        </span>
                    </div>
                    <p class="text-sm text-slate-400 truncate"><?php echo e($user->email); ?></p>
                    <?php if($user->shop_name): ?>
                    <p class="text-sm font-medium text-slate-300 mt-1">🏪 <?php echo e($user->shop_name); ?></p>
                    <?php endif; ?>
                    <?php if($user->shop_description): ?>
                    <p class="text-xs text-slate-500 mt-1 line-clamp-2"><?php echo e($user->shop_description); ?></p>
                    <?php endif; ?>
                </div>

                
                <div class="shrink-0 text-right">
                    <p class="text-xs text-slate-500">
                        <?php echo e($user->created_at->diffForHumans()); ?>

                    </p>
                    <a href="<?php echo e(route('admin.verification.show', $user)); ?>"
                       class="mt-3 inline-flex items-center gap-1 px-4 py-2 rounded-xl bg-amber-500/15 text-amber-300 text-xs font-bold hover:bg-amber-500/25 transition-colors">
                        Review
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            
            <?php if($user->seller_rejection_reason): ?>
            <div class="mt-3 p-3 rounded-xl bg-red-900/20 border border-red-800/30">
                <p class="text-xs text-red-300"><span class="font-bold">Alasan:</span> <?php echo e($user->seller_rejection_reason); ?></p>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="flex justify-center pt-2">
        <?php echo e($users->links()); ?>

    </div>
    <?php endif; ?>

</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\0_Project_VS_Code\3. Sistem Basis Data\TB-K1-Database\lapak_gaming\resources\views\admin\verification\index.blade.php ENDPATH**/ ?>