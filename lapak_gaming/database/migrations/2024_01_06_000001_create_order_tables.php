<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->unsignedBigInteger('buyer_id');
            $table->unsignedBigInteger('seller_id');
            $table->enum('status', [
                'pending_payment',
                'payment_uploaded',
                'processing',
                'delivered',
                'completed',
                'disputed',
                'cancelled'
            ])->default('pending_payment');
            
            $table->decimal('subtotal', 12, 2);
            $table->decimal('fee', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->decimal('seller_income', 12, 2)->default(0); // Total after fee
            
            $table->text('buyer_note')->nullable();
            $table->string('payment_proof')->nullable();
            $table->timestamp('payment_uploaded_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            $table->boolean('is_dispute')->default(false);
            $table->text('dispute_reason')->nullable();
            $table->timestamp('disputed_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('buyer_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('seller_id')->references('id')->on('users')->cascadeOnDelete();
            $table->index('order_number');
            $table->index(['buyer_id', 'status']);
            $table->index(['seller_id', 'status']);
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->string('product_name');
            $table->decimal('price', 12, 2);
            $table->integer('quantity')->default(1);
            $table->decimal('subtotal', 12, 2);
            $table->text('digital_content')->nullable(); // Encrypted content key/account untuk deliver
            $table->timestamp('delivered_at')->nullable();
            $table->boolean('is_buyer_confirmed')->default(false);
            $table->timestamp('buyer_confirmed_at')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->restrictOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
