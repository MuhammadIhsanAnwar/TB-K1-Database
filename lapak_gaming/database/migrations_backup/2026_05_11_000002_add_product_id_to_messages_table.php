<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('messages') || Schema::hasColumn('messages', 'product_id')) {
            return;
        }

        Schema::table('messages', function (Blueprint $table): void {
            $table->foreignId('product_id')->nullable()->after('order_id')->constrained()->nullOnDelete();
            $table->index(['product_id', 'is_read']);
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('messages') || ! Schema::hasColumn('messages', 'product_id')) {
            return;
        }

        Schema::table('messages', function (Blueprint $table): void {
            $table->dropIndex(['product_id', 'is_read']);
            $table->dropConstrainedForeignId('product_id');
        });
    }
};
