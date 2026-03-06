<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'buyer_id',
        'seller_id',
        'status',
        'subtotal',
        'fee',
        'total',
        'seller_income',
        'buyer_note',
        'payment_proof',
        'payment_uploaded_at',
        'confirmed_at',
        'completed_at',
        'is_dispute',
        'dispute_reason',
        'disputed_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'fee' => 'decimal:2',
        'total' => 'decimal:2',
        'seller_income' => 'decimal:2',
        'is_dispute' => 'boolean',
        'payment_uploaded_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
        'disputed_at' => 'datetime',
    ];

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending_payment' => 'Menunggu Pembayaran',
            'payment_uploaded' => 'Bukti Pembayaran Dikirim',
            'processing' => 'Diproses',
            'delivered' => 'Terkirim',
            'completed' => 'Selesai',
            'disputed' => 'Dispute',
            'cancelled' => 'Dibatalkan',
            default => $this->status
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending_payment' => 'warning',
            'payment_uploaded' => 'info',
            'processing' => 'info',
            'delivered' => 'info',
            'completed' => 'success',
            'disputed' => 'danger',
            'cancelled' => 'secondary',
            default => 'light'
        };
    }
}
