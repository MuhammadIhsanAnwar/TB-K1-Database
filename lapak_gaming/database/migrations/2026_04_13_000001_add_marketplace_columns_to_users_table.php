<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            // Check if columns already exist before adding
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['buyer', 'seller', 'admin'])->default('buyer')->after('password');
            }
            
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'pending', 'suspended'])->default('active')->after('role');
            }
            
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 30)->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('phone');
            }
            
            if (!Schema::hasColumn('users', 'seller_level_id')) {
                $table->foreignId('seller_level_id')->nullable()->after('avatar');
            }
            
            if (!Schema::hasColumn('users', 'suspended_at')) {
                $table->timestamp('suspended_at')->nullable()->after('seller_level_id');
            }

            // Add foreign key constraint if it doesn't exist
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
        Schema::table('users', function (Blueprint $table): void {
            try {
                $table->dropConstrainedForeignId('seller_level_id');
            } catch (\Exception $e) {
                // Constraint might not exist
            }
            
            $columns = [];
            if (Schema::hasColumn('users', 'role')) {
                $columns[] = 'role';
            }
            if (Schema::hasColumn('users', 'status')) {
                $columns[] = 'status';
            }
            if (Schema::hasColumn('users', 'phone')) {
                $columns[] = 'phone';
            }
            if (Schema::hasColumn('users', 'avatar')) {
                $columns[] = 'avatar';
            }
            if (Schema::hasColumn('users', 'suspended_at')) {
                $columns[] = 'suspended_at';
            }
            
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};