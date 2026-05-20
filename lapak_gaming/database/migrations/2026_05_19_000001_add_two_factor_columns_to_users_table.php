<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            if (! Schema::hasColumn('users', 'two_factor_enabled')) {
                $table->boolean('two_factor_enabled')->default(false)->after('last_login_device_hash');
            }

            if (! Schema::hasColumn('users', 'two_factor_methods')) {
                $table->json('two_factor_methods')->nullable()->after('two_factor_enabled');
            }

            if (! Schema::hasColumn('users', 'two_factor_google_secret')) {
                $table->string('two_factor_google_secret', 128)->nullable()->after('two_factor_methods');
            }

            if (! Schema::hasColumn('users', 'two_factor_confirmed_at')) {
                $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_google_secret');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            if (Schema::hasColumn('users', 'two_factor_confirmed_at')) {
                $table->dropColumn('two_factor_confirmed_at');
            }

            if (Schema::hasColumn('users', 'two_factor_google_secret')) {
                $table->dropColumn('two_factor_google_secret');
            }

            if (Schema::hasColumn('users', 'two_factor_methods')) {
                $table->dropColumn('two_factor_methods');
            }

            if (Schema::hasColumn('users', 'two_factor_enabled')) {
                $table->dropColumn('two_factor_enabled');
            }
        });
    }
};