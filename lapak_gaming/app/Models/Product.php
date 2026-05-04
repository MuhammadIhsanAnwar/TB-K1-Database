<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Product extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id', 'category_id', 'name', 'slug', 'description',
        'price', 'stock', 'image', 'type', 'status',
        'sold_count', 'rating', 'review_count', 'meta',
    ];

    protected function casts(): array {
        return [
            'price'   => 'decimal:2',
            'rating'  => 'decimal:2',
            'meta'    => 'array',
        ];
    }

    protected static function boot() {
        parent::boot();
        static::creating(fn($p) => $p->slug = $p->slug ?? Str::slug($p->name) . '-' . Str::random(4));
    }

    // Relations
    public function seller()    { return $this->belongsTo(User::class, 'user_id'); }
    public function category()  { return $this->belongsTo(Category::class); }
    public function reviews()   { return $this->hasMany(Review::class); }
    public function orderItems(){ return $this->hasMany(OrderItem::class); }
    public function carts()     { return $this->hasMany(Cart::class); }

    // Scopes
    public function scopeActive($q)     { return $q->where('status', 'active'); }
    public function scopeInStock($q)    { return $q->where('stock', '>', 0); }
    public function scopePopular($q)    { return $q->orderByDesc('sold_count'); }
    public function scopeTopRated($q)   { return $q->orderByDesc('rating'); }
    public function scopeOfType($q, $t) { return $q->where('type', $t); }

    // Helpers
    public function getImageUrlAttribute(): string {
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('images/default-product.png');
    }

    public function getFormattedPriceAttribute(): string {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function updateRating(): void {
        $avg = $this->reviews()->avg('rating') ?? 0;
        $count = $this->reviews()->count();
        $this->update(['rating' => round($avg, 2), 'review_count' => $count]);
    }
}