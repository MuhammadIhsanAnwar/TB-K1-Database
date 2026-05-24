<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('seller_level_benefits')) {
            Schema::create('seller_level_benefits', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('seller_level_id')->constrained()->cascadeOnDelete();
                $table->unsignedInteger('sort_order')->default(0);
                $table->string('benefit');
                $table->timestamps();

                $table->unique(['seller_level_id', 'sort_order']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_level_benefits');
    }
};
