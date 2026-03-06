<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Hanya tambahkan kolom jika belum ada
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->default('https://ui-avatars.com/api/?name=User')->after('phone');
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['buyer', 'seller', 'admin'])->default('buyer')->after('avatar');
            }
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('role');
            }
            if (!Schema::hasColumn('users', 'is_suspended')) {
                $table->boolean('is_suspended')->default(false)->after('is_active');
            }
            if (!Schema::hasColumn('users', 'suspended_reason')) {
                $table->text('suspended_reason')->nullable()->after('is_suspended');
            }
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes()->after('suspended_reason');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'avatar', 'role', 'email_verified_at',
                'is_active', 'is_suspended', 'suspended_reason', 'deleted_at'
            ]);
        });
    }
};
