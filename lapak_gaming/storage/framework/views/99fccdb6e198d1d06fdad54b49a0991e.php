<?php $__env->startSection('title', 'Pesan — Lapak Gaming'); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* WhatsApp Style Inbox */
.inbox-container {
    display: flex;
    height: calc(100vh - 80px);
    background: #0a0a0a;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #1a1a1a;
    box-shadow: 0 8px 32px rgba(0,0,0,0.5);
}

.inbox-sidebar {
    width: 350px;
    background: #111111;
    border-right: 1px solid #1a1a1a;
    display: flex;
    flex-direction: column;
}

.inbox-header {
    padding: 20px 16px;
    background: #111111;
    border-bottom: 1px solid #1a1a1a;
}

.inbox-title {
    font-size: 24px;
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 4px;
}

.inbox-subtitle {
    font-size: 14px;
    color: #888888;
}

.search-container {
    margin-top: 16px;
}

.search-input {
    width: 100%;
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    border-radius: 8px;
    padding: 10px 12px;
    color: #ffffff;
    font-size: 14px;
    outline: none;
}

.search-input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
}

.conversations-list {
    flex: 1;
    overflow-y: auto;
}

.conv-item {
    display: flex;
    align-items: center;
    padding: 16px;
    cursor: pointer;
    transition: background-color 0.15s;
    border-bottom: 1px solid #1a1a1a;
    text-decoration: none;
    position: relative;
}

.conv-item:hover {
    background: #1a1a1a;
}

.conv-item.active {
    background: #1e3a8a;
}

.conv-item.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: #2563eb;
}

.conv-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 14px;
    border: 2px solid #2a2a2a;
}

.conv-content {
    flex: 1;
    min-width: 0;
}

.conv-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
}

.conv-name {
    font-size: 16px;
    font-weight: 600;
    color: #ffffff;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.conv-time {
    font-size: 12px;
    color: #666666;
    margin-left: 8px;
    flex-shrink: 0;
}

.conv-context {
    font-size: 13px;
    color: #2563eb;
    margin-bottom: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.conv-preview {
    font-size: 14px;
    color: #888888;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.conv-preview.unread {
    color: #ffffff;
    font-weight: 500;
}

.unread-badge {
    background: #2563eb;
    color: #ffffff;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 600;
    min-width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 6px;
    margin-left: auto;
}

.empty-state {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #0a0a0a;
    color: #888888;
}

.empty-icon {
    font-size: 64px;
    margin-bottom: 16px;
}

.empty-title {
    font-size: 18px;
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 8px;
}

.empty-text {
    font-size: 14px;
    text-align: center;
    max-width: 300px;
}

@media (max-width: 768px) {
    .inbox-container {
        height: 100vh;
        border-radius: 0;
    }
    .inbox-sidebar {
        width: 100%;
    }
    .empty-state {
        display: none;
    }
}

.tab-btn {
    flex: 1;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #2a2a2a;
    background: #1a1a1a;
    color: #888;
    font-size: 14px;
    font-weight: 600;
    transition: all .2s;
}

.tab-btn:hover {
    background: #222;
    color: #fff;
}

.active-tab {
    background: #2563eb;
    color: white;
    border-color: #2563eb;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 py-4">
    <div class="inbox-container">

        
        <div class="inbox-sidebar">
            <div class="inbox-header">
                <h1 class="inbox-title">Pesan</h1>
                <p class="inbox-subtitle">Kelola chat sebagai buyer atau seller</p>
                <div class="search-container">
                    <input id="searchInput" type="text" placeholder="Cari percakapan..." class="search-input">
                </div>
                <div class="flex gap-2 mt-4">
    
                <button id="buyerTabBtn"
                    onclick="switchInboxTab('buyer')"
                    class="tab-btn active-tab">
                    Sebagai Buyer
                </button>

                <button id="sellerTabBtn"
                    onclick="switchInboxTab('seller')"
                    class="tab-btn">
                    Sebagai Seller
                </button>
</div>
            </div>

            <div class="conversations-list" id="convList">
                
            <div id="buyerTab">

            <?php $__empty_1 = true; $__currentLoopData = $buyerChats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                <?php
                    $user = auth()->user();
                    $partner = $conv->seller;
                    $unread = $conv->unreadFor($user->id);
                    $lastMsg = $conv->messages->last();
                ?>

                <a href="<?php echo e(route('chat.show', ['conversation' => $conv->id, 'role' => 'buyer'])); ?>"
                class="conv-item"
                data-name="<?php echo e(strtolower($partner?->name ?? '')); ?>">

                    <img src="<?php echo e($partner?->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($partner?->name ?? 'Seller')); ?>"
                        class="conv-avatar">

                    <div class="conv-content">

                        <div class="conv-header">
                            <span class="conv-name">
                                <?php echo e($partner?->name ?? 'Seller'); ?>

                            </span>

                            <span class="conv-time">
                                <?php echo e($conv->last_message_at?->diffForHumans(null, true)); ?>

                            </span>
                        </div>

                        <p class="conv-context">
                            🛒 Chat sebagai Buyer
                        </p>

                        <div class="flex justify-between items-center">
                            <span class="conv-preview <?php echo e($unread > 0 ? 'unread' : ''); ?>">
                                <?php echo e(Str::limit($conv->last_message, 45)); ?>

                            </span>

                            <?php if($unread > 0): ?>
                                <span class="unread-badge">
                                    <?php echo e($unread > 9 ? '9+' : $unread); ?>

                                </span>
                            <?php endif; ?>
                        </div>

                    </div>
                </a>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

            <div class="flex flex-col items-center justify-center py-16 text-center px-6">
                <div class="text-4xl mb-3">🛒</div>
                <p class="text-slate-400 text-sm font-medium">
                    Belum ada chat buyer
                </p>
            </div>

            <?php endif; ?>

            </div>

            
            <div id="sellerTab" style="display:none;">

            <?php $__empty_1 = true; $__currentLoopData = $sellerChats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                <?php
                    $user = auth()->user();
                    $partner = $conv->buyer;
                    $unread = $conv->unreadFor($user->id);
                ?>

                <a href="<?php echo e(route('chat.show', ['conversation' => $conv->id, 'role' => 'seller'])); ?>"
                class="conv-item"
                data-name="<?php echo e(strtolower($partner?->name ?? '')); ?>">

                    <img src="<?php echo e($partner?->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($partner?->name ?? 'Buyer')); ?>"
                        class="conv-avatar">

                    <div class="conv-content">

                        <div class="conv-header">
                            <span class="conv-name">
                                <?php echo e($partner?->name ?? 'Buyer'); ?>

                            </span>

                            <span class="conv-time">
                                <?php echo e($conv->last_message_at?->diffForHumans(null, true)); ?>

                            </span>
                        </div>

                        <p class="conv-context">
                            🏪 Chat sebagai Seller
                        </p>

                        <div class="flex justify-between items-center">
                            <span class="conv-preview <?php echo e($unread > 0 ? 'unread' : ''); ?>">
                                <?php echo e(Str::limit($conv->last_message, 45)); ?>

                            </span>

                            <?php if($unread > 0): ?>
                                <span class="unread-badge">
                                    <?php echo e($unread > 9 ? '9+' : $unread); ?>

                                </span>
                            <?php endif; ?>
                        </div>

                    </div>
                </a>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

            <div class="flex flex-col items-center justify-center py-16 text-center px-6">
                <div class="text-4xl mb-3">🏪</div>
                <p class="text-slate-400 text-sm font-medium">
                    Belum ada chat seller
                </p>
            </div>

<?php endif; ?>

</div>
            </div>
        </div>

        
        <div class="empty-state">
            <div class="empty-icon">💬</div>
            <h2 class="empty-title">Pilih percakapan</h2>
            <p class="empty-text">Klik salah satu percakapan di kiri untuk membukanya</p>
        </div>

    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>

function switchInboxTab(tab)
{
    const buyerTab = document.getElementById('buyerTab');
    const sellerTab = document.getElementById('sellerTab');

    const buyerBtn = document.getElementById('buyerTabBtn');
    const sellerBtn = document.getElementById('sellerTabBtn');

    buyerTab.style.display = 'none';
    sellerTab.style.display = 'none';

    buyerBtn.classList.remove('active-tab');
    sellerBtn.classList.remove('active-tab');

    if (tab === 'buyer') {
        buyerTab.style.display = 'block';
        buyerBtn.classList.add('active-tab');
    } else {
        sellerTab.style.display = 'block';
        sellerBtn.classList.add('active-tab');
    }
}

document.getElementById('searchInput').addEventListener('input', function() {

    const q = this.value.toLowerCase();

    document.querySelectorAll('.conv-item').forEach(el => {

        const name = el.getAttribute('data-name');

        el.style.display =
            name.includes(q)
            ? 'flex'
            : 'none';

    });

});

</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\0_Project_VS_Code\3. Sistem Basis Data\TB-K1-Database\lapak_gaming\resources\views\chat\inbox.blade.php ENDPATH**/ ?>