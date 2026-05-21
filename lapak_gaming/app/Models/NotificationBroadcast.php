<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MarketplaceNotification;

class NotificationBroadcast extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'link',
        'type',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    protected $appends = [
        'category',
        'category_label',
    ];

    public function deliveries()
    {
        return $this->hasMany(MarketplaceNotification::class, 'broadcast_id');
    }

    public function getCategoryAttribute(): string
    {
        $metadataCategory = data_get($this->metadata, 'category');

        if (in_array($metadataCategory, [
            MarketplaceNotification::CATEGORY_TRANSACTION,
            MarketplaceNotification::CATEGORY_EVENT_REWARD,
            MarketplaceNotification::CATEGORY_GENERAL,
        ], true)) {
            return $metadataCategory;
        }

        if ($this->type === 'admin-event_reward') {
            return MarketplaceNotification::CATEGORY_EVENT_REWARD;
        }

        return MarketplaceNotification::CATEGORY_GENERAL;
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            MarketplaceNotification::CATEGORY_TRANSACTION => 'Transaksi',
            MarketplaceNotification::CATEGORY_EVENT_REWARD => 'Event & Hadiah',
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
