<?php $__env->startSection('title', 'Chat Produk'); ?>

<?php $__env->startSection('content'); ?>
<?php
  $authId = auth()->id();
  $partnerId = $partner?->id;
?>

<div class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-900">
  <div class="flex items-center justify-between gap-3 flex-wrap">
    <div>
      <h1 class="text-2xl font-black">Chat Produk</h1>
      <p class="text-sm text-slate-500">
        <?php echo e($product->name); ?>

        <?php if($partner): ?>
          • Lawan chat: <?php echo e($partner->name); ?>

        <?php endif; ?>
      </p>
    </div>

    <a href="<?php echo e(route('products.show', $product->slug)); ?>" class="text-sm text-brand-400 hover:text-brand-300">Kembali ke produk →</a>
  </div>

  <?php if($authId === $product->seller_id && $participants->isNotEmpty()): ?>
  <div class="mt-4 flex gap-2 flex-wrap">
    <?php $__currentLoopData = $participants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $participant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <a href="<?php echo e(route('chat.product', ['product' => $product->id, 'buyer' => $participant->id])); ?>"
         class="px-3 py-1.5 rounded-xl text-xs <?php echo e(($partnerId === $participant->id) ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200'); ?>">
        <?php echo e($participant->name); ?>

      </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>
  <?php endif; ?>

  <div id="chat-box"
       data-auth-id="<?php echo e($authId); ?>"
       data-poll-url="<?php echo e($authId === $product->seller_id && $partnerId ? route('chat.product.poll', ['product' => $product->id, 'buyer' => $partnerId]) : route('chat.product.poll', ['product' => $product->id])); ?>"
       class="mt-6 h-120 overflow-y-auto rounded-[1.75rem] bg-slate-50 p-4 dark:bg-slate-950/40">
    <?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <div class="mb-3 flex <?php echo e($message->sender_id === $authId ? 'justify-end' : 'justify-start'); ?>">
        <div class="max-w-[75%] rounded-2xl px-4 py-3 text-sm <?php echo e($message->sender_id === $authId ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950' : 'bg-white text-slate-900 dark:bg-slate-900 dark:text-white'); ?>">
          <?php echo e($message->message); ?>

        </div>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <div class="text-center text-sm text-slate-500 py-8">Belum ada pesan. Mulai chat sekarang.</div>
    <?php endif; ?>
  </div>

  <?php if($partner): ?>
  <form method="POST" action="<?php echo e(route('chat.product.store', $product)); ?>" class="mt-4 flex gap-3">
    <?php echo csrf_field(); ?>
    <?php if($authId === $product->seller_id): ?>
      <input type="hidden" name="receiver_id" value="<?php echo e($partnerId); ?>">
    <?php endif; ?>
    <input id="chat-input" name="message" placeholder="Tulis pesan..."
           class="flex-1 rounded-2xl border border-slate-200 px-4 py-3 dark:border-slate-700 dark:bg-slate-950" />
    <button class="rounded-2xl bg-slate-950 px-5 py-3 font-bold text-white dark:bg-white dark:text-slate-950">Kirim</button>
  </form>
  <?php else: ?>
  <div class="mt-4 text-sm text-slate-500">
    <?php if($authId === $product->seller_id): ?>
      Belum ada buyer yang memulai chat untuk produk ini.
    <?php else: ?>
      Seller tidak tersedia untuk chat.
    <?php endif; ?>
  </div>
  <?php endif; ?>
</div>

<script>
  const chatBox = document.getElementById('chat-box');
  const authId = Number(chatBox?.dataset?.authId || 0);
  const pollUrl = chatBox?.dataset?.pollUrl || '';

  const escapeHtml = (value) => String(value)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/\"/g, '&quot;')
    .replace(/'/g, '&#039;');

  const scrollBottom = () => {
    chatBox.scrollTop = chatBox.scrollHeight;
  };

  const renderMessages = (messages) => {
    if (!Array.isArray(messages) || messages.length === 0) {
      return;
    }

    chatBox.innerHTML = messages.map((message) => `
      <div class="mb-3 flex ${Number(message.sender_id) === authId ? 'justify-end' : 'justify-start'}">
        <div class="max-w-[75%] rounded-2xl px-4 py-3 text-sm ${Number(message.sender_id) === authId ? 'bg-slate-950 text-white dark:bg-white dark:text-slate-950' : 'bg-white text-slate-900 dark:bg-slate-900 dark:text-white'}">
          ${escapeHtml(message.message)}
        </div>
      </div>
    `).join('');

    scrollBottom();
  };

  const pollMessages = async () => {
    if (!pollUrl) {
      return;
    }

    try {
      const response = await fetch(pollUrl, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      });
      if (!response.ok) {
        return;
      }
      const payload = await response.json();
      renderMessages(payload.messages);
    } catch (error) {
      console.error(error);
    }
  };

  scrollBottom();
  setInterval(pollMessages, 3000);
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\0_Project_VS_Code\3. Sistem Basis Data\TB-K1-Database\lapak_gaming\resources\views\chat\product.blade.php ENDPATH**/ ?>