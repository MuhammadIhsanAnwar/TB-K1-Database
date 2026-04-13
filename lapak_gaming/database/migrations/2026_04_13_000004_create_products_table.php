<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 14, 2);
            $table->decimal('sale_price', 14, 2)->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->string('file_path')->nullable();
            $table->longText('delivery_content')->nullable();
            $table->boolean('is_auto_delivery')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_trending')->default(false);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->decimal('rating_average', 4, 2)->default(0);
            $table->unsignedInteger('review_count')->default(0);
            $table->unsignedBigInteger('views_count')->default(0);
            $table->unsignedBigInteger('downloads_count')->default(0);
            $table->timestamps();

            $table->index(['seller_id', 'status']);
            $table->index(['category_id', 'is_featured', 'is_trending']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};