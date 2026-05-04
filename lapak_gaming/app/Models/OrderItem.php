<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model {
    protected $fillable = [
        'order_id', 'product_id', 'seller_id',
        'product_name', 'price', 'quantity',
        'subtotal', 'delivery_status', 'delivery_data',
    ];

    protected function casts(): array {
        return ['price' => 'decimal:2', 'subtotal' => 'decimal:2'];
    }

    public function order()   { return $this->belongsTo(Order::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function seller()  { return $this->belongsTo(User::class, 'seller_id'); }
    public function review()  { return $this->hasOne(Review::class); }
}