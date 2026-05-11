<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users') || Schema::hasColumn('users', 'is_seller')) {
            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            $table->boolean('is_seller')->default(false)->after('role');
        });

        DB::table('users')->where('role', 'seller')->update(['is_seller' => true]);

        if (Schema::hasColumn('users', 'user_type')) {
            DB::table('users')->whereIn('user_type', ['seller', 'mixed'])->update(['is_seller' => true]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('users') || ! Schema::hasColumn('users', 'is_seller')) {
            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('is_seller');
        });
    }
};
