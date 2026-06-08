<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hero_cards', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Cosmic Warfare');
            $table->string('subtitle')->default('User ID: 8847291 • Zone: Global');
            $table->string('option1_value')->default('250');
            $table->string('option1_price')->default('Rp 45.000');
            $table->string('option2_value')->default('750');
            $table->string('option2_price')->default('Rp 120.000');
            $table->string('payment_text')->default('250 Diamonds - Rp 45.000');
            $table->string('region_text')->default('SEA Server');
            $table->string('promo_badge')->default('BONUS +20% TODAY');
            $table->string('receipt_title')->default('Order #92841');
            $table->string('receipt_desc')->default('Delivered instantly in 2s');
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hero_cards');
    }
};
