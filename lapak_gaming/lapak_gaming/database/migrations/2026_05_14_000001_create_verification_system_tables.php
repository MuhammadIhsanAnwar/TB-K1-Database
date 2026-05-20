<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Verification System Migration
 *
 * 1. Extends users.seller_status enum to include: under_review, need_revision, suspended
 * 2. Creates verification_logs table (audit trail for every admin action)
 * 3. Creates clarification_messages table (back-and-forth between admin and user)
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Extend seller_status enum ────────────────────────────────────
        // MySQL: ALTER COLUMN to extend enum values (safe – no data loss)
        DB::statement("ALTER TABLE users MODIFY seller_status ENUM(
            'none',
            'pending',
            'under_review',
            'need_revision',
            'approved',
            'rejected',
            'suspended'
        ) NOT NULL DEFAULT 'none'");

        // Add seller_reviewed_at so admin can track when they last touched it
        if (! Schema::hasColumn('users', 'seller_reviewed_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('seller_reviewed_at')->nullable()->after('seller_rejection_reason');
            });
        }

        // Add seller_submitted_at for accurate timeline
        if (! Schema::hasColumn('users', 'seller_submitted_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('seller_submitted_at')->nullable()->after('seller_reviewed_at');
            });
        }

        // ── 2. Verification Logs ─────────────────────────────────────────────
        if (! Schema::hasTable('verification_logs')) {
            Schema::create('verification_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
                $table->enum('action', [
                    'submitted',        // User submitted seller application
                    'under_review',     // Admin started reviewing
                    'revision_requested', // Admin asked for revision
                    'approved',         // Admin approved
                    'rejected',         // Admin rejected
                    'suspended',        // Admin suspended
                    'resubmitted',      // User resubmitted after revision
                    'reinstated',       // Admin reinstated from suspension
                ]);
                $table->text('notes')->nullable();  // Admin notes / rejection reason
                $table->json('meta')->nullable();   // Extra context (files requested, etc.)
                $table->timestamps();

                $table->index(['user_id', 'created_at']);
                $table->index(['admin_id', 'created_at']);
            });
        }

        // ── 3. Clarification Messages ────────────────────────────────────────
        if (! Schema::hasTable('clarification_messages')) {
            Schema::create('clarification_messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
                $table->enum('sender_type', ['admin', 'user']);
                $table->text('message');
                $table->json('attachments')->nullable(); // [{name, path, url}]
                $table->timestamp('read_at')->nullable();
                $table->softDeletes();
                $table->timestamps();

                $table->index(['user_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('clarification_messages');
        Schema::dropIfExists('verification_logs');

        // Revert enum to original 4 values
        DB::statement("ALTER TABLE users MODIFY seller_status ENUM(
            'none','pending','approved','rejected'
        ) NOT NULL DEFAULT 'none'");

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['seller_reviewed_at', 'seller_submitted_at']);
        });
    }
};