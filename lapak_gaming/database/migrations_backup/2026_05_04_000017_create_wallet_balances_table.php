<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('wallet_balances')) {
            Schema::create('wallet_balances', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('wallet_id')->unique()->constrained()->cascadeOnDelete();
                $table->decimal('balance', 14, 2)->default(0);
                $table->decimal('available_balance', 14, 2)->default(0);
                $table->decimal('locked_balance', 14, 2)->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_balances');
    }
};
