<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('user_profiles')) {
            return;
        }

        DB::statement('ALTER TABLE `user_profiles` MODIFY `birth_date` DATE NULL');
    }

    public function down(): void
    {
        if (! Schema::hasTable('user_profiles')) {
            return;
        }

        DB::statement('ALTER TABLE `user_profiles` MODIFY `birth_date` DATE NOT NULL');
    }
};
