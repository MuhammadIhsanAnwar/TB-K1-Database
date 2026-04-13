<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->enum('role', ['buyer', 'seller', 'admin'])->default('buyer')->after('password');
            $table->enum('status', ['active', 'pending', 'suspended'])->default('active')->after('role');
            $table->string('phone', 30)->nullable()->after('status');
            $table->string('avatar')->nullable()->after('phone');
            $table->foreignId('seller_level_id')->nullable()->constrained()->nullOnDelete()->after('avatar');
            $table->timestamp('suspended_at')->nullable()->after('seller_level_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('seller_level_id');
            $table->dropColumn(['role', 'status', 'phone', 'avatar', 'suspended_at']);
        });
    }
};