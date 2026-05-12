<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model {
    public const STATUS_PENDING_PAYMENT = 'pending_payment';
    public const STATUS_PAYMENT_UPLOADED = 'payment_uploaded';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_DISPUTED = 'disputed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_REFUNDED = 'refunded';

    protected $fillable = [
        'invoice_number', 'buyer_id', 'seller_id', 
        'status', 'payment_method', 'payment_proof', 'delivery_notes',
        'tracking_code', 'due_at', 'completed_at', 'disputed_at', 'notes', 'metadata',
    ];

    protected function casts(): array {
        return [
            'paid_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    protected static function boot() {
        parent::boot();
        static::creating(function($order) {
            if (Schema::hasColumn($order->getTable(), 'order_code')) {
                do {
                    $order->order_code = 'LG-' . now()->format('Ymd') . '-' . strtoupper(Str::random(8));
                } while (self::where('order_code', $order->order_code)->exists());
            }
        });
    }

    public function getOrderCodeAttribute(): string
    {
        return $this->attributes['order_code'] ?? $this->attributes['invoice_number'] ?? '';
    }

    public function buyer()     { return $this->belongsTo(User::class, 'buyer_id'); }
    public function seller(): BelongsTo { return $this->belongsTo(User::class, 'seller_id'); }
    public function items()     { return $this->hasMany(OrderItem::class); }
    public function financial(): HasOne { return $this->hasOne(OrderFinancial::class); }

    public function scopePending($q)    { return $q->where('status', self::STATUS_PENDING_PAYMENT); }
    public function scopeCompleted($q)  { return $q->where('status', 'completed'); }

    public function getStatusLabelAttribute(): string {
        return match($this->status) {
            self::STATUS_PENDING_PAYMENT => 'Menunggu Pembayaran',
            self::STATUS_PAYMENT_UPLOADED => 'Pembayaran Dikirim',
            self::STATUS_PROCESSING => 'Diproses Seller',
            self::STATUS_DELIVERED => 'Dikirim',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_DISPUTED => 'Sengketa',
            self::STATUS_CANCELLED => 'Dibatalkan',
            self::STATUS_REFUNDED => 'Dikembalikan',
            default      => $this->status,
        };
    }

    public function getStatusColorAttribute(): string {
        return match($this->status) {
            self::STATUS_PENDING_PAYMENT => 'yellow',
            self::STATUS_PAYMENT_UPLOADED => 'blue',
            self::STATUS_PROCESSING => 'indigo',
            self::STATUS_DELIVERED => 'sky',
            self::STATUS_COMPLETED => 'green',
            self::STATUS_DISPUTED => 'orange',
            self::STATUS_CANCELLED => 'red',
            self::STATUS_REFUNDED => 'gray',
            default      => 'gray',
        };
    }

    public function getSubtotalAttribute(): float
    {
        return (float) ($this->financial?->subtotal ?? 0);
    }

    public function getFeeAmountAttribute(): float
    {
        return (float) ($this->financial?->fee_amount ?? 0);
    }

    public function getFeeAttribute(): float
    {
        return $this->getFeeAmountAttribute();
    }

    public function getEscrowAmountAttribute(): float
    {
        return (float) ($this->financial?->escrow_amount ?? 0);
    }

    public function getGrandTotalAttribute(): float
    {
        return (float) ($this->financial?->grand_total ?? 0);
    }

    public function getTotalPriceAttribute(): float
    {
        return $this->getGrandTotalAttribute();
    }
}