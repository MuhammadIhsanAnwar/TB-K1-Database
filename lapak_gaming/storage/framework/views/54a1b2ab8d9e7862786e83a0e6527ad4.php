<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-950 py-12 px-4">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-white mb-8">My Wishlist</h1>
        
        <div class="bg-gray-900 rounded-xl p-8 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            <p class="text-gray-400 text-lg">Your wishlist is empty</p>
            <p class="text-gray-500 text-sm mt-2">Add items to your wishlist to keep track of products you love</p>
            <a href="<?php echo e(route('marketplace.home')); ?>" class="inline-block mt-6 bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                Continue Shopping
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\0_Project_VS_Code\3. Sistem Basis Data\TB-K1-Database\lapak_gaming\resources\views\wishlist\index.blade.php ENDPATH**/ ?>