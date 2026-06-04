<?php
// File: public/fix.php
chdir(dirname(__DIR__));
echo '<pre>';
echo "🔧 Running composer install...\n";
system('composer install 2>&1');
echo "\n✅ Composer install complete!\n";
echo "\n🔧 Running php artisan optimize:clear...\n";
system('php artisan optimize:clear 2>&1');
echo "\n✅ Cache cleared!\n";
echo "\n✨ All fixed! Now try: https://lapakgaming.neoverse.my.id/artisan-terminal";
echo '</pre>';
?>