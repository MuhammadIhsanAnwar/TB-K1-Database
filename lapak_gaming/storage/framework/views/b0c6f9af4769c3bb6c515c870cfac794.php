<?php $__env->startSection('title', 'Detail Verifikasi — ' . $user->name); ?>

<?php $__env->startPush('styles'); ?>
<style>
.panel { background: rgba(13,20,33,.9); border: 1px solid rgba(30,45,69,.8); border-radius: 16px; }
.status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 999px; font-size: .75rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; }
.s-pending       { background: rgba(245,158,11,.12); color: #fbbf24; border: 1px solid rgba(245,158,11,.25); }
.s-under_review  { background: rgba(99,102,241,.12); color: #a5b4fc; border: 1px solid rgba(99,102,241,.25); }
.s-need_revision { background: rgba(249,115,22,.12); color: #fb923c; border: 1px solid rgba(249,115,22,.25); }
.s-approved      { background: rgba(16,185,129,.12); color: #34d399; border: 1px solid rgba(16,185,129,.25); }
.s-rejected      { background: rgba(239,68,68,.12);  color: #f87171; border: 1px solid rgba(239,68,68,.25); }
.s-suspended     { background: rgba(100,116,139,.12); color: #94a3b8; border: 1px solid rgba(100,116,139,.25); }
.timeline-line { width: 2px; background: linear-gradient(to bottom, rgba(59,130,246,.4), transparent); margin: 0 auto; }
.chat-bubble-admin { background: rgba(99,102,241,.15); border: 1px solid rgba(99,102,241,.2); border-radius: 0 16px 16px 16px; }
.chat-bubble-user  { background: rgba(30,45,69,.8);    border: 1px solid rgba(30,45,69,1);   border-radius: 16px 0 16px 16px; margin-left: auto; }
.action-btn { display: inline-flex; align-items: center; gap: 6px; padding: .5rem 1rem; border-radius: 12px; font-size: .8rem; font-weight: 700; transition: all .15s; cursor: pointer; border: none; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen py-8 px-4">
<div class="mx-auto max-w-6xl space-y-6">

    
    <a href="<?php echo e(route('admin.verification.index')); ?>" class="inline-flex items-center gap-2 text-sm text-slate-400 hover:text-white">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Daftar Verifikasi
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        
        <div class="space-y-5">

            
            <div class="panel p-6">
                <div class="flex items-center gap-4 mb-5">
                    <img src="<?php echo e($user->shop_photo ? asset('storage/' . $user->shop_photo) : $user->avatar_url); ?>"
                         alt="<?php echo e($user->name); ?>"
                         class="w-16 h-16 rounded-2xl object-cover border border-slate-700">
                    <div class="min-w-0">
                        <h2 class="font-black text-white text-lg truncate"><?php echo e($user->name); ?></h2>
                        <p class="text-slate-400 text-sm truncate"><?php echo e($user->email); ?></p>
                        <span class="status-badge s-<?php echo e($user->seller_status); ?> mt-2">
                            <?php echo e(match($user->seller_status) {
                                'pending'       => '⏳ Pending',
                                'under_review'  => '🔍 Sedang Direview',
                                'need_revision' => '✏️ Perlu Revisi',
                                'approved'      => '✅ Disetujui',
                                'rejected'      => '❌ Ditolak',
                                'suspended'     => '🚫 Suspend',
                                default         => ucfirst($user->seller_status),
                            }); ?>

                        </span>
                    </div>
                </div>

                <div class="space-y-3 text-sm">
                    <?php if($user->shop_name): ?>
                    <div class="flex gap-3">
                        <span class="text-slate-500 shrink-0">Nama Toko</span>
                        <span class="text-white font-medium"><?php echo e($user->shop_name); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if($user->shop_description): ?>
                    <div>
                        <span class="text-slate-500 block mb-1">Deskripsi Toko</span>
                        <p class="text-slate-300 leading-relaxed"><?php echo e($user->shop_description); ?></p>
                    </div>
                    <?php endif; ?>
                    <div class="flex gap-3">
                        <span class="text-slate-500 shrink-0">Bergabung</span>
                        <span class="text-slate-300"><?php echo e($user->created_at->format('d M Y')); ?></span>
                    </div>
                    <?php if($user->seller_reviewed_at): ?>
                    <div class="flex gap-3">
                        <span class="text-slate-500 shrink-0">Terakhir Review</span>
                        <span class="text-slate-300"><?php echo e($user->seller_reviewed_at->format('d M Y H:i')); ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if($user->shop_photo): ?>
                <div class="mt-5">
                    <p class="text-xs text-slate-500 mb-2 font-medium uppercase tracking-wider">Foto Toko</p>
                    <img src="<?php echo e(asset('storage/' . $user->shop_photo)); ?>"
                         alt="Foto toko" class="w-full rounded-xl object-cover max-h-48 border border-slate-700">
                </div>
                <?php endif; ?>
            </div>

            
            <div class="panel p-5 space-y-3">
                <h3 class="font-bold text-white text-sm uppercase tracking-wider mb-4">Tindakan Admin</h3>

                <?php if(in_array($user->seller_status, ['pending', 'need_revision'])): ?>
                <button onclick="showActionModal('review')" class="action-btn w-full bg-indigo-500/15 text-indigo-300 hover:bg-indigo-500/25">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Mulai Review
                </button>
                <?php endif; ?>

                <?php if(in_array($user->seller_status, ['pending', 'under_review', 'need_revision'])): ?>
                <button onclick="showActionModal('approve')" class="action-btn w-full bg-emerald-500/15 text-emerald-300 hover:bg-emerald-500/25">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Setujui Pengajuan
                </button>
                <button onclick="showActionModal('revise')" class="action-btn w-full bg-orange-500/15 text-orange-300 hover:bg-orange-500/25">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Minta Revisi
                </button>
                <button onclick="showActionModal('reject')" class="action-btn w-full bg-red-500/15 text-red-300 hover:bg-red-500/25">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Tolak Pengajuan
                </button>
                <?php endif; ?>

                <?php if($user->seller_status === 'approved'): ?>
                <button onclick="showActionModal('suspend')" class="action-btn w-full bg-slate-500/15 text-slate-300 hover:bg-slate-500/25">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    Suspend Akun
                </button>
                <?php endif; ?>

                <?php if($user->seller_status === 'suspended'): ?>
                <button onclick="showActionModal('reinstate')" class="action-btn w-full bg-emerald-500/15 text-emerald-300 hover:bg-emerald-500/25">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Pulihkan Akun
                </button>
                <?php endif; ?>

                
                <button onclick="document.getElementById('clariBox').scrollIntoView({behavior:'smooth'})" class="action-btn w-full bg-brand-500/15 text-brand-300 hover:bg-brand-500/25">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    Kirim Klarifikasi
                </button>
            </div>
        </div>

        
        <div class="lg:col-span-2 space-y-5">

            
            <div class="panel p-6">
                <h3 class="font-bold text-white mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Riwayat Verifikasi
                </h3>

                <?php if($logs->isEmpty()): ?>
                <p class="text-slate-500 text-sm text-center py-6">Belum ada riwayat aktivitas.</p>
                <?php else: ?>
                <div class="space-y-0">
                    <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $color = match($log->action) {
                            'submitted','resubmitted' => ['dot'=>'bg-brand-500','text'=>'text-brand-400','bg'=>'bg-brand-500/10'],
                            'under_review'            => ['dot'=>'bg-indigo-500','text'=>'text-indigo-400','bg'=>'bg-indigo-500/10'],
                            'revision_requested'      => ['dot'=>'bg-orange-500','text'=>'text-orange-400','bg'=>'bg-orange-500/10'],
                            'approved','reinstated'   => ['dot'=>'bg-emerald-500','text'=>'text-emerald-400','bg'=>'bg-emerald-500/10'],
                            'rejected','suspended'    => ['dot'=>'bg-red-500','text'=>'text-red-400','bg'=>'bg-red-500/10'],
                            default                   => ['dot'=>'bg-slate-500','text'=>'text-slate-400','bg'=>'bg-slate-500/10'],
                        };
                    ?>
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-3 h-3 rounded-full <?php echo e($color['dot']); ?> shrink-0 mt-1"></div>
                            <?php if(!$loop->last): ?>
                            <div class="w-px flex-1 bg-slate-800 my-1"></div>
                            <?php endif; ?>
                        </div>
                        <div class="pb-5 flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-bold text-white text-sm"><?php echo e($log->actionLabel()); ?></span>
                                <span class="text-xs text-slate-500"><?php echo e($log->created_at->format('d M Y H:i')); ?></span>
                            </div>
                            <?php if($log->admin): ?>
                            <p class="text-xs text-slate-500 mt-0.5">oleh <span class="text-slate-400"><?php echo e($log->admin->name); ?></span></p>
                            <?php endif; ?>
                            <?php if($log->notes): ?>
                            <div class="mt-2 p-3 rounded-xl <?php echo e($color['bg']); ?> border border-white/5">
                                <p class="text-sm text-slate-300"><?php echo e($log->notes); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>
            </div>

            
            <div class="panel p-6" id="clariBox">
                <h3 class="font-bold text-white mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    Klarifikasi
                </h3>

                <div class="space-y-4 max-h-80 overflow-y-auto pr-1 mb-5">
                    <?php $__empty_1 = true; $__currentLoopData = $clarifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="max-w-[85%] <?php echo e($msg->isFromAdmin() ? '' : 'ml-auto'); ?>">
                        <div class="<?php echo e($msg->isFromAdmin() ? 'chat-bubble-admin' : 'chat-bubble-user'); ?> p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <img src="<?php echo e($msg->sender->avatar_url); ?>" class="w-6 h-6 rounded-full" alt="">
                                <span class="text-xs font-bold <?php echo e($msg->isFromAdmin() ? 'text-indigo-300' : 'text-slate-300'); ?>">
                                    <?php echo e($msg->isFromAdmin() ? '🔑 ' . $msg->sender->name . ' (Admin)' : '👤 ' . $msg->sender->name); ?>

                                </span>
                            </div>
                            <p class="text-sm text-slate-200 leading-relaxed whitespace-pre-wrap"><?php echo e($msg->message); ?></p>
                            <p class="text-xs text-slate-500 mt-2"><?php echo e($msg->created_at->format('d M Y H:i')); ?></p>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-slate-500 text-sm text-center py-4">Belum ada pesan klarifikasi.</p>
                    <?php endif; ?>
                </div>

                
                <form method="POST" action="<?php echo e(route('admin.verification.clarify', $user)); ?>" enctype="multipart/form-data"
                      x-data="{sending: false}" @submit="sending = true">
                    <?php echo csrf_field(); ?>
                    <div class="space-y-3">
                        <textarea name="message" rows="3" required
                            placeholder="Tulis pesan klarifikasi untuk seller ini..."
                            class="w-full rounded-xl bg-slate-900 border border-slate-700 text-slate-200 placeholder-slate-500 px-4 py-3 text-sm resize-none focus:outline-none focus:border-brand-500 transition-colors"></textarea>
                        <div class="flex gap-3 justify-end">
                            <button type="submit" :disabled="sending"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-brand-600 text-white text-sm font-bold hover:bg-brand-500 disabled:opacity-60 transition-colors">
                                <span x-show="!sending">Kirim Pesan</span>
                                <span x-show="sending">Mengirim...</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>

</div>
</div>


<div id="actionModal" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-700 rounded-2xl p-6 w-full max-w-md">
        <h3 id="modalTitle" class="text-white font-bold text-lg mb-4">Konfirmasi Tindakan</h3>
        <form id="actionForm" method="POST">
            <?php echo csrf_field(); ?>
            <div id="notesWrapper" class="mb-4">
                <label class="text-sm text-slate-400 block mb-2" id="notesLabel">Catatan / Alasan</label>
                <textarea name="notes" id="notesInput" rows="4"
                    class="w-full rounded-xl bg-slate-800 border border-slate-700 text-slate-200 placeholder-slate-500 px-4 py-3 text-sm resize-none focus:outline-none focus:border-brand-500"
                    placeholder="Tulis catatan atau alasan..."></textarea>
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-xl border border-slate-700 text-slate-400 text-sm hover:text-white transition-colors">Batal</button>
                <button type="submit" id="modalSubmitBtn" class="px-5 py-2 rounded-xl bg-amber-500 text-slate-950 font-bold text-sm hover:bg-amber-400 transition-colors">Konfirmasi</button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
const routes = {
    review:    '<?php echo e(route('admin.verification.review', $user)); ?>',
    revise:    '<?php echo e(route('admin.verification.revise', $user)); ?>',
    approve:   '<?php echo e(route('admin.verification.approve', $user)); ?>',
    reject:    '<?php echo e(route('admin.verification.reject', $user)); ?>',
    suspend:   '<?php echo e(route('admin.verification.suspend', $user)); ?>',
    reinstate: '<?php echo e(route('admin.verification.reinstate', $user)); ?>',
};

const configs = {
    review:    { title: 'Mulai Review', label: 'Catatan (opsional)', required: false, color: 'bg-indigo-500' },
    revise:    { title: 'Minta Revisi', label: 'Apa yang perlu diperbaiki?', required: true, color: 'bg-orange-500' },
    approve:   { title: 'Setujui Pengajuan ✅', label: 'Catatan (opsional)', required: false, color: 'bg-emerald-500' },
    reject:    { title: 'Tolak Pengajuan ❌', label: 'Alasan penolakan (wajib diisi)', required: true, color: 'bg-red-500' },
    suspend:   { title: 'Suspend Akun Seller', label: 'Alasan suspend (wajib diisi)', required: true, color: 'bg-slate-500' },
    reinstate: { title: 'Pulihkan Akun Seller ✅', label: 'Catatan (opsional)', required: false, color: 'bg-emerald-500' },
};

function showActionModal(action) {
    const cfg = configs[action];
    document.getElementById('modalTitle').textContent = cfg.title;
    document.getElementById('notesLabel').textContent = cfg.label;
    document.getElementById('notesInput').required = cfg.required;
    document.getElementById('notesInput').placeholder = cfg.required ? 'Wajib diisi...' : 'Opsional...';
    document.getElementById('actionForm').action = routes[action];
    document.getElementById('modalSubmitBtn').className = `px-5 py-2 rounded-xl ${cfg.color} text-white font-bold text-sm hover:opacity-90 transition-opacity`;
    document.getElementById('actionModal').classList.remove('hidden');
    document.getElementById('actionModal').classList.add('flex');
}

function closeModal() {
    document.getElementById('actionModal').classList.add('hidden');
    document.getElementById('actionModal').classList.remove('flex');
}

document.getElementById('actionModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\0_Project_VS_Code\3. Sistem Basis Data\TB-K1-Database\lapak_gaming\resources\views\admin\verification\show.blade.php ENDPATH**/ ?>