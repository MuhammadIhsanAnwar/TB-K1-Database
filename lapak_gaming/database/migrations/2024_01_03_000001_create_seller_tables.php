<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('seller_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('min_sales')->default(0);
            $table->integer('min_rating')->default(0);
            $table->decimal('commission_rate', 5, 2)->default(10.00); // Persentase komisi
            $table->text('benefits')->nullable();
            $table->string('badge_color')->default('#6366f1');
            $table->timestamps();
        });

        Schema::create('seller_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('shop_name');
            $table->text('shop_description')->nullable();
            $table->string('shop_avatar')->nullable();
            $table->string('shop_banner')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->unsignedBigInteger('seller_level_id')->default(1);
            $table->integer('total_sales')->default(0);
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_reviews')->default(0);
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->boolean('is_banned')->default(false);
            $table->timestamp('banned_at')->nullable();
            $table->text('ban_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('seller_level_id')->references('id')->on('seller_levels')->restrictOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('seller_accounts');
        Schema::dropIfExists('seller_levels');
    }
};
