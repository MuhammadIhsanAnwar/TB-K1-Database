<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('banners')) {
            Schema::create('banners', function (Blueprint $table): void {
                $table->id();
                $table->string('title');
                $table->string('subtitle')->nullable();
                $table->string('image_url')->nullable();
                $table->string('image_path')->nullable();
                $table->string('link_url')->nullable();
                $table->enum('position', ['hero', 'featured', 'sidebar'])->default('hero');
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index(['position', 'is_active']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
