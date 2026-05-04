<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model {
    protected $fillable = [
        'order_code', 'user_id', 'subtotal', 'fee',
        'total_price', 'status', 'payment_method',
        'payment_proof', 'paid_at', 'completed_at', 'notes',
    ];

    protected function casts(): array {
        return [
            'paid_at' => 'datetime',
            'completed_at' => 'datetime',
            'subtotal'    => 'decimal:2',
            'fee'         => 'decimal:2',
            'total_price' => 'decimal:2',
        ];
    }

    protected static function boot() {
        parent::boot();
        static::creating(function($order) {
            $order->order_code = 'LG-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        });
    }

    public function buyer()     { return $this->belongsTo(User::class, 'user_id'); }
    public function items()     { return $this->hasMany(OrderItem::class); }

    public function scopePending($q)    { return $q->where('status', 'pending'); }
    public function scopeCompleted($q)  { return $q->where('status', 'completed'); }

    public function getStatusLabelAttribute(): string {
        return match($this->status) {
            'pending'    => 'Menunggu Pembayaran',
            'paid'       => 'Sudah Dibayar',
            'processing' => 'Diproses Seller',
            'completed'  => 'Selesai',
            'cancelled'  => 'Dibatalkan',
            'refunded'   => 'Dikembalikan',
            default      => $this->status,
        };
    }

    public function getStatusColorAttribute(): string {
        return match($this->status) {
            'pending'    => 'yellow',
            'paid'       => 'blue',
            'processing' => 'indigo',
            'completed'  => 'green',
            'cancelled'  => 'red',
            'refunded'   => 'gray',
            default      => 'gray',
        };
    }
}