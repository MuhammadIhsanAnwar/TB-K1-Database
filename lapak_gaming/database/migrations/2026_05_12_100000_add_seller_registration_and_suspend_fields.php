<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Seller registration workflow: none → pending → approved / rejected
            if (! Schema::hasColumn('users', 'seller_status')) {
                $column = Schema::hasColumn('users', 'role') ? 'role' : null;
                $table->enum('seller_status', ['none', 'pending', 'approved', 'rejected'])
                    ->default('none')
                    ->after($column ?? 'password');
            }

            if (! Schema::hasColumn('users', 'seller_rejection_reason')) {
                $table->text('seller_rejection_reason')->nullable()->after('seller_status');
            }

            // Shop profile (submitted during seller registration)
            if (! Schema::hasColumn('users', 'shop_name')) {
                $table->string('shop_name', 255)->nullable()->after('seller_rejection_reason');
            }

            if (! Schema::hasColumn('users', 'shop_photo')) {
                $table->string('shop_photo', 500)->nullable()->after('shop_name');
            }

            if (! Schema::hasColumn('users', 'shop_description')) {
                $table->text('shop_description')->nullable()->after('shop_photo');
            }

            // Suspend reason so user can see why they were suspended
            if (! Schema::hasColumn('users', 'suspend_reason')) {
                $table->text('suspend_reason')->nullable()->after('suspended_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'seller_status',
                'seller_rejection_reason',
                'shop_name',
                'shop_photo',
                'shop_description',
                'suspend_reason',
            ]);
        });
    }
};