<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('orders') && !Schema::hasColumn('orders', 'order_code')) {
            Schema::table('orders', function (Blueprint $table): void {
                $table->string('order_code')->unique()->after('invoice_number');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'order_code')) {
            Schema::table('orders', function (Blueprint $table): void {
                $table->dropColumn('order_code');
            });
        }
    }
};
