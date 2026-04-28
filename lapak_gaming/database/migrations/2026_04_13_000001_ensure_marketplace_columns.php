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
                $table->unsignedBigInteger('seller_level_id')->nullable();
            }

            if (!Schema::hasColumn('users', 'suspended_at')) {
                $table->timestamp('suspended_at')->nullable();
            }
        });

        // Step 2: Add foreign key constraint if it doesn't exist
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
                    Schema::table('users', function (Blueprint $table) {
                        $table->foreign('seller_level_id')
                            ->references('id')
                            ->on('seller_levels')
                            ->nullOnDelete();
                    });
                }
            } catch (\Exception $e) {
                // Foreign key already exists or other issue - continue
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            try {
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexes = $sm->listTableForeignKeys('users');

                foreach ($indexes as $index) {
                    if (!empty($index->getLocalColumns()) && $index->getLocalColumns()[0] === 'seller_level_id') {
                        $table->dropForeign(['seller_level_id']);
                        break;
                    }
                }
            } catch (\Exception $e) {
                // Constraint doesn't exist
            }

            $columns = [];
            $existingColumns = [
                'role', 'status', 'phone', 'avatar', 'seller_level_id', 'suspended_at'
            ];

            foreach ($existingColumns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $columns[] = $column;
                }
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
