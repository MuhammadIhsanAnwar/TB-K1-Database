<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('banners')) {
            Schema::table('banners', function (Blueprint $table): void {
                if (!Schema::hasColumn('banners', 'image_path')) {
                    $table->string('image_path')->nullable()->after('image_url');
                }
                
                if (Schema::hasColumn('banners', 'image_url')) {
                    $table->string('image_url')->nullable()->change();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('banners')) {
            Schema::table('banners', function (Blueprint $table): void {
                if (Schema::hasColumn('banners', 'image_path')) {
                    $table->dropColumn('image_path');
                }
            });
        }
    }
};
