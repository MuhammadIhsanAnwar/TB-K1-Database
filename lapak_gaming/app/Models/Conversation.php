<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'buyer_id',
        'seller_id',
        'product_id',
        'order_id',
        'last_message',
        'last_message_at',
        'last_message_sender_id',
        'unread_buyer',
        'unread_seller',
        'pinned_by_seller',
        'archived_by_buyer_at',
        'archived_by_seller_at',
    ];

    protected $casts = [
        'last_message_at'          => 'datetime',
        'pinned_by_seller'         => 'boolean',
        'archived_by_buyer_at'     => 'datetime',
        'archived_by_seller_at'    => 'datetime',
    ];

    // ── Relations ──────────────────────────────────────────────────────────

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function lastMessageSender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_message_sender_id');
    }

    // ── Helpers ────────────────────────────────────────────────────────────

    /**
     * Return or create the conversation for a buyer↔seller+product pair.
     */
    public static function findOrCreateForProduct(int $buyerId, int $sellerId, int $productId): static
    {
        return static::firstOrCreate(
            [
                'buyer_id'   => $buyerId,
                'seller_id'  => $sellerId,
                'product_id' => $productId,
                'order_id'   => null,
            ],
            ['last_message_at' => now()]
        );
    }

    /**
     * Return or create the conversation for a buyer↔seller+order pair.
     */
    public static function findOrCreateForOrder(int $buyerId, int $sellerId, int $orderId): static
    {
        return static::firstOrCreate(
            [
                'buyer_id'  => $buyerId,
                'seller_id' => $sellerId,
                'order_id'  => $orderId,
                'product_id' => null,
            ],
            ['last_message_at' => now()]
        );
    }

    /**
     * Increment unread count for the OTHER party when a new message arrives.
     */
    public function incrementUnread(int $senderId): void
    {
        if ($senderId === $this->buyer_id) {
            $this->increment('unread_seller');
        } else {
            $this->increment('unread_buyer');
        }
    }

    /**
     * Reset unread count for a given user (they just opened the conversation).
     */
    public function markReadFor(int $userId): void
    {
        if ($userId === $this->buyer_id) {
            $this->update(['unread_buyer' => 0]);
        } elseif ($userId === $this->seller_id) {
            $this->update(['unread_seller' => 0]);
        }
    }

    /**
     * Update denormalised last-message info after a new message is saved.
     */
    public function updateLastMessage(Message $message): void
    {
        $this->update([
            'last_message'           => mb_substr($message->message, 0, 100),
            'last_message_at'        => $message->created_at,
            'last_message_sender_id' => $message->sender_id,
        ]);
    }

    /**
     * Unread count for a given user (sidebar badge).
     */
    public function unreadFor(int $userId): int
    {
        return $userId === $this->buyer_id
            ? (int) $this->unread_buyer
            : (int) $this->unread_seller;
    }

    /**
     * The "other party" from the perspective of $userId.
     */
    public function partner(int $userId): ?User
    {
        if ($userId === $this->buyer_id) {
            return $this->seller;
        }
        return $this->buyer;
    }
}