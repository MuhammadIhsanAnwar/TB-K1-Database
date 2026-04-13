<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seller_levels', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('minimum_orders')->default(0);
            $table->decimal('minimum_revenue', 14, 2)->default(0);
            $table->decimal('fee_percent', 5, 2)->default(0);
            $table->string('badge_color')->default('slate');
            $table->json('benefits')->nullable();
            $table->boolean('auto_approve')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_levels');
    }
};