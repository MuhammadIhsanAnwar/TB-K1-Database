<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users') || Schema::hasColumn('users', 'username')) {
            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            $table->string('username', 50)->nullable()->unique()->after('name');
        });

        $users = DB::table('users')->select('id', 'name', 'email')->get();

        foreach ($users as $user) {
            $baseUsername = Str::slug($user->name ?: explode('@', (string) $user->email)[0], '_') ?: 'user';
            $username = $baseUsername;
            $suffix = 1;

            while (DB::table('users')->where('username', $username)->where('id', '!=', $user->id)->exists()) {
                $username = $baseUsername . '_' . $suffix++;
            }

            DB::table('users')->where('id', $user->id)->update(['username' => $username]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('users') || ! Schema::hasColumn('users', 'username')) {
            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            $table->dropUnique(['username']);
            $table->dropColumn('username');
        });
    }
};