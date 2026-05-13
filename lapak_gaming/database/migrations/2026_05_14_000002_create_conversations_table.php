<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Modern Chat System Migration
 *
 * 1. Creates conversations table (thread wrapper)
 * 2. Adds conversation_id to messages for grouping
 * 3. Adds pinned_by_seller, archived_at to conversations
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Conversations ────────────────────────────────────────────────
        if (! Schema::hasTable('conversations')) {
            Schema::create('conversations', function (Blueprint $table) {
                $table->id();

                // The two parties
                $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();

                // Context (one of these will be set)
                $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();

                // Denormalised last-message info for sidebar performance
                $table->text('last_message')->nullable();
                $table->timestamp('last_message_at')->nullable();
                $table->foreignId('last_message_sender_id')->nullable()->constrained('users')->nullOnDelete();

                // Unread counts per side
                $table->unsignedInteger('unread_buyer')->default(0);
                $table->unsignedInteger('unread_seller')->default(0);

                // UX extras
                $table->boolean('pinned_by_seller')->default(false);
                $table->timestamp('archived_by_buyer_at')->nullable();
                $table->timestamp('archived_by_seller_at')->nullable();

                $table->timestamps();
                $table->softDeletes();

                // Unique conversation per pair+context
                $table->unique(['buyer_id', 'seller_id', 'product_id', 'order_id'], 'unique_conversation');
                $table->index(['seller_id', 'last_message_at']);
                $table->index(['buyer_id', 'last_message_at']);
            });
        }

        // ── 2. Add conversation_id to messages ──────────────────────────────
        if (! Schema::hasColumn('messages', 'conversation_id')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->foreignId('conversation_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('conversations')
                    ->nullOnDelete();
                $table->index('conversation_id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['conversation_id']);
            $table->dropColumn('conversation_id');
        });

        Schema::dropIfExists('conversations');
    }
};