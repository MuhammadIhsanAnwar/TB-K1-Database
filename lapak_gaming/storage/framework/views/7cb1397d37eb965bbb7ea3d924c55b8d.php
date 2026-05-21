<?php $__env->startComponent('mail::message'); ?>
<div style="text-align: center; margin-bottom: 16px;">
    <img src="<?php echo e($logoUrl); ?>" alt="<?php echo e($appName); ?> logo" style="width: 72px; height: 72px; border-radius: 12px; object-fit: contain;">
</div>

# Halo <?php echo e($recipientName); ?>


Terima kasih telah mendaftar di <?php echo e($appName); ?>.
Silakan klik tombol di bawah untuk mengaktifkan akun Anda dan menyelesaikan pendaftaran.

<?php $__env->startComponent('mail::button', ['url' => $url]); ?>
Aktivasi Akun
<?php echo $__env->renderComponent(); ?>

Jika Anda tidak merasa melakukan pendaftaran ini, abaikan email ini.

Salam,<br>
Tim <?php echo e($appName); ?>

<?php echo $__env->renderComponent(); ?>
<?php /**PATH D:\0_Project_VS_Code\3. Sistem Basis Data\TB-K1-Database\lapak_gaming\resources\views\emails\verify-email.blade.php ENDPATH**/ ?>