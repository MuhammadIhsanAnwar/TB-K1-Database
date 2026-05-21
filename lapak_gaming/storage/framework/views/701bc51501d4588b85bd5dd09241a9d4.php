<?php $__env->startSection('title', 'Chat Produk — ' . $product->name); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 py-8">
    <a href="<?php echo e(route('products.show', $product->slug)); ?>" class="inline-flex items-center gap-2 text-sm text-slate-400 hover:text-white mb-6 transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Produk
    </a>

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white">Chat Produk</h1>
            <p class="text-slate-400 text-sm mt-1">🎮 <?php echo e($product->name); ?></p>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route('chat.inbox')); ?>" class="inline-flex items-center gap-2 rounded-2xl border border-slate-700 bg-slate-900/70 px-4 py-2 text-sm text-slate-200 hover:bg-slate-800 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72A2.032 2.032 0 015 14.158V11a6.002 6.002 0 004-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                Chat Semua Pelanggan
            </a>
        </div>
    </div>

    <?php if($conversations->isEmpty()): ?>
    <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-16 text-center">
        <div class="text-5xl mb-4">💬</div>
        <p class="text-white font-bold">Belum ada pesan</p>
        <p class="text-slate-400 text-sm mt-1">Belum ada pembeli yang menghubungi untuk produk ini.</p>
    </div>
    <?php else: ?>
    <div class="space-y-3">
        <?php $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $buyer = $conv->buyer; $unread = $conv->unread_seller; ?>
        <a href="<?php echo e(route('chat.show', $conv)); ?>"
           class="flex items-center gap-4 p-4 rounded-2xl border border-slate-800 bg-slate-900/50 hover:border-slate-600 hover:bg-slate-900 transition-all">
            <div class="relative">
                <img src="<?php echo e($buyer?->avatar_url); ?>" class="w-12 h-12 rounded-full object-cover" alt="">
                <?php if($unread > 0): ?>
                <span class="absolute -top-1 -right-1 bg-brand-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center"><?php echo e($unread); ?></span>
                <?php endif; ?>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex justify-between items-center">
                    <h3 class="font-bold text-white"><?php echo e($buyer?->name ?? 'Pembeli'); ?></h3>
                    <span class="text-xs text-slate-500"><?php echo e($conv->last_message_at?->diffForHumans()); ?></span>
                </div>
                <p class="text-sm text-slate-400 truncate mt-0.5">
                    <?php echo e($conv->last_message ?? 'Belum ada pesan'); ?>

                </p>
            </div>
            <svg class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\0_Project_VS_Code\3. Sistem Basis Data\TB-K1-Database\lapak_gaming\resources\views\chat\product-seller.blade.php ENDPATH**/ ?>