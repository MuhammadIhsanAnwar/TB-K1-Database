<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('order_items') || ! Schema::hasColumn('order_items', 'seller_id')) {
            return;
        }

        Schema::table('order_items', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('seller_id');
        });

        Schema::table('order_items', function (Blueprint $table): void {
            // Recreate foreign key to users table (seller is stored in users table)
            $table->foreignId('seller_id')->nullable()->after('product_id')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('order_items') || ! Schema::hasColumn('order_items', 'seller_id')) {
            return;
        }

        Schema::table('order_items', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('seller_id');
        });
    }
};
