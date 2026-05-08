<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ensure all marketplace columns exist in users table
     * Handle cases where partial migration happened
     */
    public function up(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        // First, ensure seller_levels table exists
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

        Schema::table('users', function (Blueprint $table) {
            // Step 1: Ensure all columns exist
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['buyer', 'seller', 'admin'])->default('buyer')->after('password');
            }

            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'pending', 'suspended'])->default('active');
            }

            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 30)->nullable();
            }

            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable();
            }

            if (!Schema::hasColumn('users', 'seller_level_id')) {
                $table->foreignId('seller_level_id')->nullable()->after('avatar');
            }

            if (!Schema::hasColumn('users', 'suspended_at')) {
                $table->timestamp('suspended_at')->nullable();
            }

            // Step 2: Add foreign key constraint if needed
            if (Schema::hasColumn('users', 'seller_level_id')) {
                try {
                    $sm = Schema::getConnection()->getDoctrineSchemaManager();
                    $indexes = $sm->listTableForeignKeys('users');
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
                } catch (\Exception $e) {
                    // Foreign key might already exist or seller_levels table might not exist yet
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            try {
                $table->dropConstrainedForeignId('seller_level_id');
            } catch (\Exception $e) {
                // Constraint might not exist
            }
        });
    }
};
