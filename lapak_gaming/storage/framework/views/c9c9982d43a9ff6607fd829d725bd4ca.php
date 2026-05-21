<?php $__env->startSection('title', 'Checkout'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold text-white mb-8">Checkout</h1>

    <?php if($errors->any()): ?>
    <div class="mb-6 p-4 bg-red-900/30 border border-red-700 rounded-xl">
        <h3 class="text-red-300 font-bold mb-2">Terjadi Kesalahan:</h3>
        <ul class="list-disc list-inside space-y-1 text-red-200 text-sm">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    <form action="<?php echo e(route('cart.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="payment_method" value="balance">
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 mb-6">
            <h2 class="font-bold text-white mb-4">Item yang Dibeli</h2>
            <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex gap-4 py-3 border-b border-gray-800 last:border-0">
                    <img src="<?php echo e($item->product->image_url); ?>" class="w-14 h-14 rounded-lg object-cover" alt="">
                    <div class="flex-1">
                        <p class="text-sm text-gray-200"><?php echo e($item->product->name); ?></p>
                        <p class="text-xs text-gray-500">× <?php echo e($item->quantity); ?></p>
                    </div>
                    <p class="text-sm text-white font-bold">Rp <?php echo e(number_format($item->product->price * $item->quantity, 0, ',', '.')); ?></p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 mb-6">
            <h2 class="font-bold text-white mb-4">Metode Pembayaran</h2>
            <div class="rounded-xl border border-violet-500/20 bg-violet-900/20 px-4 py-4 text-sm text-violet-100">
                <div class="font-semibold text-white">Saldo Wallet</div>
                <div class="mt-1 text-violet-100/80">Pembayaran akan diproses langsung dari saldo wallet kamu.</div>
                <div class="mt-2 text-xs text-violet-200/80">Saldo tersedia: Rp <?php echo e(number_format(auth()->user()->balance, 0, ',', '.')); ?></div>
            </div>
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 mb-6">
            <div class="flex justify-between text-sm text-gray-400 mb-2">
                <span>Subtotal</span>
                <span>Rp <?php echo e(number_format($subtotal, 0, ',', '.')); ?></span>
            </div>
            <div class="flex justify-between text-sm text-gray-400 mb-2">
                <span>Biaya Platform</span>
                <span>Rp <?php echo e(number_format($fee, 0, ',', '.')); ?></span>
            </div>
            <div class="flex justify-between text-base font-bold text-white">
                <span>Total Pembayaran</span>
                <span>Rp <?php echo e(number_format($total, 0, ',', '.')); ?></span>
            </div>
        </div>

        <button type="submit" class="w-full bg-violet-600 hover:bg-violet-700 text-white font-bold py-4 rounded-xl text-lg transition">
            Buat Pesanan →
        </button>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\0_Project_VS_Code\3. Sistem Basis Data\TB-K1-Database\lapak_gaming\resources\views\orders\checkout.blade.php ENDPATH**/ ?>