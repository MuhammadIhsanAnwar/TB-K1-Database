<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ensure seller_status column exists and has all needed enum values
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'seller_status')) {
            // For MySQL: modify seller_status to include all values
            DB::statement("ALTER TABLE users MODIFY seller_status ENUM(
                'none',
                'pending',
                'under_review',
                'need_revision',
                'approved',
                'rejected',
                'suspended'
            ) NOT NULL DEFAULT 'none'");
        }
    }

    public function down(): void
    {
        // Revert to basic enum values (for rollback)
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'seller_status')) {
            DB::statement("ALTER TABLE users MODIFY seller_status ENUM(
                'none',
                'pending',
                'approved',
                'rejected'
            ) NOT NULL DEFAULT 'none'");
        }
    }
};
