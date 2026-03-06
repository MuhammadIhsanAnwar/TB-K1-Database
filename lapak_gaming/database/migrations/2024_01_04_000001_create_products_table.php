<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('category_id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('price', 12, 2);
            $table->string('thumbnail')->nullable();
            $table->integer('stock')->default(0); // Untuk digital products
            $table->integer('sold')->default(0);
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_reviews')->default(0);
            $table->integer('view_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->text('requirements')->nullable(); // Untuk game/software requirements
            $table->text('delivery_method')->nullable(); // Email, WhatsApp, Direct Link
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('seller_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('category_id')->references('id')->on('categories')->restrictOnDelete();
            $table->index('slug');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
