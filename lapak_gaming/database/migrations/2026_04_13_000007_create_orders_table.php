<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('seller_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('invoice_number')->unique();
            $table->enum('status', ['pending_payment', 'payment_uploaded', 'processing', 'delivered', 'completed', 'disputed', 'cancelled'])->default('pending_payment');
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('fee_amount', 14, 2)->default(0);
            $table->decimal('escrow_amount', 14, 2)->default(0);
            $table->decimal('grand_total', 14, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->string('payment_proof')->nullable();
            $table->text('delivery_notes')->nullable();
            $table->string('tracking_code')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('disputed_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['buyer_id', 'status']);
            $table->index(['seller_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};