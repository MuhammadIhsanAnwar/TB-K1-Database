<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model {
    protected $fillable = [
        'order_id', 'product_id',
        'name_snapshot', 'price_snapshot', 'quantity',
        'delivery_data', 'status',
    ];

    protected function casts(): array {
        return ['price_snapshot' => 'decimal:2'];
    }

    public function order()   { return $this->belongsTo(Order::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function review()  { return $this->hasOne(Review::class); }
}