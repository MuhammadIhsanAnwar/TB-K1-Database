<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('order_financials')) {
            Schema::create('order_financials', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
                $table->decimal('subtotal', 14, 2)->default(0);
                $table->decimal('fee_amount', 14, 2)->default(0);
                $table->decimal('escrow_amount', 14, 2)->default(0);
                $table->decimal('grand_total', 14, 2)->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('order_financials');
    }
};
