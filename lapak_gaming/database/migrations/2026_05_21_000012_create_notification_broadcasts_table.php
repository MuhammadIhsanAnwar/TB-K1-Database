<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('notification_broadcasts')) {
            Schema::create('notification_broadcasts', function (Blueprint $table): void {
                $table->id();
                $table->string('title');
                $table->text('body');
                $table->string('link')->nullable();
                $table->string('type')->default('system');
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('marketplace_notifications') && ! Schema::hasColumn('marketplace_notifications', 'broadcast_id')) {
            Schema::table('marketplace_notifications', function (Blueprint $table): void {
                $table->foreignId('broadcast_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('notification_broadcasts')
                    ->nullOnDelete();
            });
        }

        if (Schema::hasTable('marketplace_notifications')) {
            if (Schema::hasColumn('marketplace_notifications', 'title')) {
                DB::statement('ALTER TABLE marketplace_notifications MODIFY title VARCHAR(255) NULL');
            }
            if (Schema::hasColumn('marketplace_notifications', 'body')) {
                DB::statement('ALTER TABLE marketplace_notifications MODIFY body TEXT NULL');
            }
            if (Schema::hasColumn('marketplace_notifications', 'type')) {
                DB::statement('ALTER TABLE marketplace_notifications MODIFY type VARCHAR(255) NULL DEFAULT NULL');
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('marketplace_notifications') && Schema::hasColumn('marketplace_notifications', 'broadcast_id')) {
            Schema::table('marketplace_notifications', function (Blueprint $table): void {
                $table->dropConstrainedForeignId('broadcast_id');
            });
        }

        Schema::dropIfExists('notification_broadcasts');

        if (Schema::hasTable('marketplace_notifications')) {
            if (Schema::hasColumn('marketplace_notifications', 'title')) {
                DB::statement('ALTER TABLE marketplace_notifications MODIFY title VARCHAR(255) NOT NULL');
            }
            if (Schema::hasColumn('marketplace_notifications', 'body')) {
                DB::statement('ALTER TABLE marketplace_notifications MODIFY body TEXT NOT NULL');
            }
            if (Schema::hasColumn('marketplace_notifications', 'type')) {
                DB::statement('ALTER TABLE marketplace_notifications MODIFY type VARCHAR(255) NOT NULL DEFAULT \'system\'');
            }
        }
    }
};
