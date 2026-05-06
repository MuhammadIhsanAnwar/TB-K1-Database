<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add foreign key to sellers table only if seller_levels table exists
        try {
            if (Schema::hasTable('sellers') && Schema::hasTable('seller_levels')) {
                Schema::table('sellers', function (Blueprint $table) {
                    $table->foreign('seller_level_id')
                        ->references('id')
                        ->on('seller_levels')
                        ->nullOnDelete();
                });
            }
        } catch (\Exception $e) {
            // Silently fail if constraint already exists or tables don't exist
        }
    }

    public function down(): void
    {
        try {
            if (Schema::hasTable('sellers')) {
                Schema::table('sellers', function (Blueprint $table) {
                    $table->dropForeign(['seller_level_id']);
                });
            }
        } catch (\Exception $e) {
            // Silently fail if constraint doesn't exist
        }
    }
};
