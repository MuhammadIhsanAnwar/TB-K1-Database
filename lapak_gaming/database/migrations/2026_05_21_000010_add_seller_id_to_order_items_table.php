<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('order_items') && ! Schema::hasColumn('order_items', 'seller_id')) {
            Schema::table('order_items', function (Blueprint $table): void {
                // nullable because older rows may not have a seller
                $table->foreignId('seller_id')->nullable()->after('product_id')->constrained('sellers')->nullOnDelete();
                $table->index(['seller_id']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('order_items') && Schema::hasColumn('order_items', 'seller_id')) {
            Schema::table('order_items', function (Blueprint $table): void {
                $table->dropConstrainedForeignId('seller_id');
            });
        }
    }
};
