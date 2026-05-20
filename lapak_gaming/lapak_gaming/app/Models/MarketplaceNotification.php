<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceNotification extends Model
{
    use HasFactory;

    public const CATEGORY_TRANSACTION = 'transaction';
    public const CATEGORY_EVENT_REWARD = 'event_reward';
    public const CATEGORY_GENERAL = 'general';

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'link',
        'type',
        'is_read',
        'read_at',
        'metadata',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'metadata' => 'array',
    ];

    protected $appends = [
        'category',
        'category_label',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCategoryAttribute(): string
    {
        $metadataCategory = data_get($this->metadata, 'category');

        if (in_array($metadataCategory, [
            self::CATEGORY_TRANSACTION,
            self::CATEGORY_EVENT_REWARD,
            self::CATEGORY_GENERAL,
        ], true)) {
            return $metadataCategory;
        }

        if ($this->type === 'admin-event_reward') {
            return self::CATEGORY_EVENT_REWARD;
        }

        if ($this->isTransactionType()) {
            return self::CATEGORY_TRANSACTION;
        }

        return self::CATEGORY_GENERAL;
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            self::CATEGORY_TRANSACTION => 'Transaksi',
            self::CATEGORY_EVENT_REWARD => 'Event & Hadiah',
            default => 'Umum',
        };
    }

    public function isTransactionType(): bool
    {
        $type = (string) $this->type;

        return $type === 'transaction'
            || str_starts_with($type, 'order-')
            || str_starts_with($type, 'payment-')
            || str_starts_with($type, 'wallet-')
            || in_array($type, ['deposit', 'withdraw', 'escrow_hold'], true);
    }
}
