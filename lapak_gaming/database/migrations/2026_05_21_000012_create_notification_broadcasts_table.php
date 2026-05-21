<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_broadcasts', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->string('link')->nullable();
            $table->string('type')->default('system');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::table('marketplace_notifications', function (Blueprint $table): void {
            $table->foreignId('broadcast_id')
                ->nullable()
                ->after('id')
                ->constrained('notification_broadcasts')
                ->nullOnDelete();
        });

        DB::statement('ALTER TABLE marketplace_notifications MODIFY title VARCHAR(255) NULL');
        DB::statement('ALTER TABLE marketplace_notifications MODIFY body TEXT NULL');
        DB::statement('ALTER TABLE marketplace_notifications MODIFY type VARCHAR(255) NULL DEFAULT NULL');
    }

    public function down(): void
    {
        Schema::table('marketplace_notifications', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('broadcast_id');
        });

        Schema::dropIfExists('notification_broadcasts');

        DB::statement('ALTER TABLE marketplace_notifications MODIFY title VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE marketplace_notifications MODIFY body TEXT NOT NULL');
        DB::statement('ALTER TABLE marketplace_notifications MODIFY type VARCHAR(255) NOT NULL DEFAULT \'system\'');
    }
};
