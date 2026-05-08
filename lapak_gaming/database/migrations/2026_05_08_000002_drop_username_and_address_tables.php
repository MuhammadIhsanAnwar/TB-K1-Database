<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            if (! Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 30)->nullable()->after('status');
            }

            if (! Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('phone');
            }
        });

        if (Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table): void {
                try {
                    $table->dropUnique(['username']);
                } catch (\Throwable $exception) {
                    // Ignore if the unique index name differs or does not exist
                }
            });

            Schema::table('users', function (Blueprint $table): void {
                $table->dropColumn('username');
            });
        }

        if (Schema::hasTable('user_addresses')) {
            Schema::dropIfExists('user_addresses');
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            if (! Schema::hasColumn('users', 'username')) {
                $table->string('username', 50)->nullable()->unique()->after('name');
            }
        });

        if (! Schema::hasTable('user_addresses')) {
            Schema::create('user_addresses', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
                $table->string('province', 100);
                $table->string('regency', 100);
                $table->string('district', 100);
                $table->string('village', 100);
                $table->string('postal_code', 10);
                $table->text('full_address');
                $table->timestamps();
            });
        }
    }
};
