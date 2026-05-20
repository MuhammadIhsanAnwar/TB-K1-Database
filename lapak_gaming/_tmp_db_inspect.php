<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "TRIGGERS\n";
foreach (DB::select('SHOW TRIGGERS') as $trigger) {
    echo $trigger->Trigger . ' | ' . $trigger->Event . ' | ' . $trigger->Timing . ' | ' . $trigger->Statement . PHP_EOL;
}

echo "PRODUCT 5\n";
var_export(DB::table('products')->where('id', 5)->first());
echo PHP_EOL;