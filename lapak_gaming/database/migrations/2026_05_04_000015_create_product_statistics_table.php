<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('product_statistics')) {
            Schema::create('product_statistics', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('product_id')->unique()->constrained()->cascadeOnDelete();
                $table->unsignedBigInteger('sold_count')->default(0);
                $table->decimal('rating_average', 4, 2)->default(0);
                $table->unsignedInteger('review_count')->default(0);
                $table->unsignedBigInteger('views_count')->default(0);
                $table->unsignedBigInteger('downloads_count')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_statistics');
    }
};
