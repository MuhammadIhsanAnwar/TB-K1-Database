<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('product_comments')) {
            Schema::create('product_comments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('parent_comment_id')->nullable()->constrained('product_comments')->onDelete('cascade');
                $table->text('content');
                $table->unsignedTinyInteger('rating')->nullable()->between(1, 5);
                $table->boolean('is_verified_buyer')->default(false);
                $table->enum('status', ['approved', 'pending', 'rejected'])->default('approved');
                $table->softDeletes();
                $table->timestamps();

                $table->index(['product_id', 'status']);
                $table->index(['user_id', 'created_at']);
                $table->index(['parent_comment_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_comments');
    }
};
