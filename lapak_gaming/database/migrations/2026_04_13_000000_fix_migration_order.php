<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Perbaiki urutan foreign key constraint
     * Migrasi ini menghapus constraint yang error dan memastikan table seller_levels ada sebelum add constraint
     */
    public function up(): void
    {
        // 1. Pastikan seller_levels table dibuat terlebih dahulu (jika belum)
        if (!Schema::hasTable('seller_levels')) {
            Schema::create('seller_levels', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('description')->nullable();
                $table->integer('min_rating')->default(0);
                $table->integer('min_sales')->default(0);
                $table->decimal('commission_rate', 5, 2)->default(10.00);
                $table->timestamps();
            });
        }

        // 2. Jika seller_level_id column sudah ada di users tapi constraint error, drop dan recreate dengan benar
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                // Drop foreign key jika ada
                try {
                    if (Schema::hasColumn('users', 'seller_level_id')) {
                        // Cek apakah constraint sudah ada
                        $sm = Schema::getConnection()->getDoctrineSchemaManager();
                        $indexes = $sm->listTableForeignKeys('users');
                        $hasFK = false;
                        
                        foreach ($indexes as $index) {
                            if ($index->getLocalColumns()[0] === 'seller_level_id') {
                                $hasFK = true;
                                break;
                            }
                        }
                        
                        // Jika constraint sudah ada, drop dulu
                        if ($hasFK) {
                            // Gunakan raw SQL untuk drop constraint yang error
                            Schema::getConnection()->statement('ALTER TABLE users DROP FOREIGN KEY users_seller_level_id_foreign');
                        }
                    }
                } catch (\Exception $e) {
                    // Ignore jika constraint tidak ada
                }
            });

            // 3. Add constraint dengan benar setelah seller_levels sudah pasti ada
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'seller_level_id') && !Schema::hasColumn('users', '_constraint_added')) {
                    try {
                        $table->foreign('seller_level_id')
                            ->references('id')
                            ->on('seller_levels')
                            ->nullOnDelete();
                    } catch (\Exception $e) {
                        // Constraint sudah ada
                    }
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            try {
                $table->dropConstrainedForeignId('seller_level_id');
            } catch (\Exception $e) {
                // Ignore jika constraint tidak ada
            }
        });
    }
};
