<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create buyers table
        Schema::create('buyers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone', 30)->nullable();
            $table->string('avatar')->nullable();
            $table->enum('status', ['active', 'pending', 'suspended'])->default('active');
            $table->timestamp('suspended_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->index('email');
            $table->index('username');
        });

        // Create sellers table
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone', 30)->nullable();
            $table->string('avatar')->nullable();
            $table->unsignedBigInteger('seller_level_id')->nullable();
            $table->enum('status', ['active', 'pending', 'suspended'])->default('active');
            $table->timestamp('suspended_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->index('email');
            $table->index('username');
        });

        // Add user_type to users table to maintain backward compatibility
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'user_type')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('user_type', ['buyer', 'seller', 'mixed'])->default('mixed')->after('role');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('buyers');
        Schema::dropIfExists('sellers');

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'user_type')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('user_type');
            });
        }
    }
};
