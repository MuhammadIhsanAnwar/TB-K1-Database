<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Modern Chat Features Migration
 *
 * Menambahkan dukungan:
 *  – Edit pesan  (edited_at)
 *  – Delete for me  (deleted_for_sender_at / deleted_for_receiver_at)
 *  – Delete for everyone  (deleted_for_everyone_at)
 *  – Role pengirim  (sender_role)
 *  – Attachment / gambar  (attachment_path, attachment_type)
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── messages ──────────────────────────────────────────────────────────
        Schema::table('messages', function (Blueprint $table) {
            if (! Schema::hasColumn('messages', 'edited_at')) {
                $table->timestamp('edited_at')->nullable()->after('read_at');
            }
            if (! Schema::hasColumn('messages', 'deleted_for_sender_at')) {
                $table->timestamp('deleted_for_sender_at')->nullable()->after('edited_at');
            }
            if (! Schema::hasColumn('messages', 'deleted_for_receiver_at')) {
                $table->timestamp('deleted_for_receiver_at')->nullable()->after('deleted_for_sender_at');
            }
            if (! Schema::hasColumn('messages', 'deleted_for_everyone_at')) {
                $table->timestamp('deleted_for_everyone_at')->nullable()->after('deleted_for_receiver_at');
            }
            if (! Schema::hasColumn('messages', 'sender_role')) {
                $table->string('sender_role', 20)->default('user')->after('sender_id');
                // 'user' | 'seller' | 'admin'
            }
            if (! Schema::hasColumn('messages', 'attachment_path')) {
                $table->string('attachment_path', 500)->nullable()->after('message');
            }
            if (! Schema::hasColumn('messages', 'attachment_type')) {
                $table->string('attachment_type', 50)->nullable()->after('attachment_path');
                // 'image' | 'file'
            }
        });

        // ── conversations: typing support ─────────────────────────────────────
        Schema::table('conversations', function (Blueprint $table) {
            if (! Schema::hasColumn('conversations', 'buyer_typing_at')) {
                $table->timestamp('buyer_typing_at')->nullable()->after('unread_seller');
            }
            if (! Schema::hasColumn('conversations', 'seller_typing_at')) {
                $table->timestamp('seller_typing_at')->nullable()->after('buyer_typing_at');
            }
            if (! Schema::hasColumn('conversations', 'buyer_last_seen_at')) {
                $table->timestamp('buyer_last_seen_at')->nullable()->after('seller_typing_at');
            }
            if (! Schema::hasColumn('conversations', 'seller_last_seen_at')) {
                $table->timestamp('seller_last_seen_at')->nullable()->after('buyer_last_seen_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn([
                'edited_at',
                'deleted_for_sender_at',
                'deleted_for_receiver_at',
                'deleted_for_everyone_at',
                'sender_role',
                'attachment_path',
                'attachment_type',
            ]);
        });

        Schema::table('conversations', function (Blueprint $table) {
            $table->dropColumn([
                'buyer_typing_at',
                'seller_typing_at',
                'buyer_last_seen_at',
                'seller_last_seen_at',
            ]);
        });
    }
};