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
                // Use users table for seller reference to match orders.seller_id which references users
                $table->foreignId('seller_id')->nullable()->after('product_id')->constrained('users')->nullOnDelete();
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
