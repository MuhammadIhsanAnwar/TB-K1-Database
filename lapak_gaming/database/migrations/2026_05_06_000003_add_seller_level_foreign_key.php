<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add foreign key to sellers table if not exists
        if (Schema::hasTable('sellers') && Schema::hasTable('seller_levels')) {
            Schema::table('sellers', function (Blueprint $table) {
                // Check if foreign key doesn't exist
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexes = $sm->listTableForeignKeys('sellers');
                $hasFK = false;
                
                foreach ($indexes as $index) {
                    if (!empty($index->getLocalColumns()) && $index->getLocalColumns()[0] === 'seller_level_id') {
                        $hasFK = true;
                        break;
                    }
                }
                
                if (!$hasFK) {
                    $table->foreign('seller_level_id')
                        ->references('id')
                        ->on('seller_levels')
                        ->nullOnDelete();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('sellers')) {
            Schema::table('sellers', function (Blueprint $table) {
                try {
                    $table->dropForeign(['seller_level_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist
                }
            });
        }
    }
};
