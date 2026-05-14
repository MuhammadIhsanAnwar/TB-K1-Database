<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('banners')) {
            return;
        }

        DB::table('banners')
            ->where('position', 'sidebar')
            ->update(['position' => 'featured']);

        DB::statement("ALTER TABLE banners MODIFY position ENUM('hero', 'featured') NOT NULL DEFAULT 'hero'");
    }

    public function down(): void
    {
        if (!Schema::hasTable('banners')) {
            return;
        }

        DB::statement("ALTER TABLE banners MODIFY position ENUM('hero', 'featured', 'sidebar') NOT NULL DEFAULT 'hero'");
    }
};
