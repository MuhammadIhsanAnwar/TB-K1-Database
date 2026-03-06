<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->decimal('balance', 12, 2)->default(0);
            $table->decimal('hold_balance', 12, 2)->default(0); // Dana dalam escrow
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wallet_id');
            $table->enum('type', ['deposit', 'withdrawal', 'transfer', 'order_payment', 'order_refund', 'commission', 'fee']);
            $table->decimal('amount', 12, 2);
            $table->decimal('balance_before', 12, 2);
            $table->decimal('balance_after', 12, 2);
            $table->text('description')->nullable();
            $table->string('reference_id')->nullable();
            $table->string('reference_type')->nullable(); // order_id, withdrawal_id, etc
            $table->boolean('is_approved')->default(true);
            $table->timestamps();

            $table->foreign('wallet_id')->references('id')->on('wallets')->cascadeOnDelete();
            $table->index(['wallet_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('wallet_transactions');
        Schema::dropIfExists('wallets');
    }
};
