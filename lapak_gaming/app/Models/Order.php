<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING_PAYMENT = 'pending_payment';
    public const STATUS_PAYMENT_UPLOADED = 'payment_uploaded';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_DISPUTED = 'disputed';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'buyer_id',
        'seller_id',
        'invoice_number',
        'status',
        'subtotal',
        'fee_amount',
        'escrow_amount',
        'grand_total',
        'payment_method',
        'payment_proof',
        'delivery_notes',
        'tracking_code',
        'due_at',
        'completed_at',
        'disputed_at',
        'metadata',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'fee_amount' => 'decimal:2',
        'escrow_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'due_at' => 'datetime',
        'completed_at' => 'datetime',
        'disputed_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}