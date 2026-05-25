<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;
use App\Support\MarketplaceCategoryCatalog;

class Product extends Model {
    use HasFactory;

    protected $fillable = [
        'seller_id', 'category_id', 'name', 'slug', 'description',
        'price', 'sale_price', 'stock', 'file_path', 'delivery_content',
        'is_auto_delivery', 'is_featured', 'is_trending', 'type',
        'status', 'meta',
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
    public function seller()    { return $this->belongsTo(User::class, 'seller_id'); }
    public function category()  { return $this->belongsTo(Category::class); }
    public function reviews()   { return $this->hasMany(Review::class); }
    public function comments()  { return $this->hasMany(ProductComment::class); }
    public function orderItems(){ return $this->hasMany(OrderItem::class); }
    public function carts()     { return $this->hasMany(Cart::class); }
    public function statistics(): HasOne { return $this->hasOne(ProductStatistic::class); }

    // Scopes
    public function scopeActive($q)     { return $q->where('status', 'published'); }
    public function scopePublished($q)   { return $q->where('status', 'published'); }
    public function scopeArchived($q)    { return $q->where('status', 'archived'); }
    public function scopeInStock($q)    { return $q->where('stock', '>', 0); }
    public function scopeSearch(Builder $q, string $term): Builder
    {
        $term = trim($term);

        if ($term === '') {
            return $q;
        }

        return $q->where(function (Builder $query) use ($term): void {
            $query->where('name', 'like', '%' . $term . '%')
                ->orWhere('description', 'like', '%' . $term . '%')
                ->orWhere('type', 'like', '%' . $term . '%');
        });
    }

    public function scopePopular(Builder $q): Builder
    {
        return $q->orderByDesc(
            ProductStatistic::select('sold_count')
                ->whereColumn('product_statistics.product_id', 'products.id')
                ->limit(1)
        );
    }

    public function scopeMostViewed(Builder $q): Builder
    {
        return $q->orderByDesc(
            ProductStatistic::select('views_count')
                ->whereColumn('product_statistics.product_id', 'products.id')
                ->limit(1)
        );
    }

    public function scopeTopRated(Builder $q): Builder
    {
        return $q->orderByDesc(
            ProductStatistic::select('rating_average')
                ->whereColumn('product_statistics.product_id', 'products.id')
                ->limit(1)
        );
    }
    public function scopeOfType($q, $t) { return $q->where('type', $t); }

    // Helpers
    public function getImagePathsAttribute(): array
    {
        $rawPath = $this->attributes['file_path'] ?? null;

        if (! $rawPath) {
            return [];
        }

        if (is_array($rawPath)) {
            return array_values(array_filter($rawPath));
        }

        $decoded = json_decode((string) $rawPath, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return array_values(array_filter($decoded));
        }

        return array_values(array_filter(array_map('trim', explode('|', (string) $rawPath))));
    }

    public function getImageAttribute(): ?string
    {
        return $this->image_paths[0] ?? null;
    }

    public function getTypeLabelAttribute(): string
    {
        return MarketplaceCategoryCatalog::labelForType($this->type);
    }

    public function getImageUrlAttribute(): string {
        // Primary: first image path derived from file_path
        $imagePath = $this->image_paths[0] ?? null;

        // Fallback: legacy fields (in case some rows store a single image path)
        if (! $imagePath) {
            $imagePath = $this->image ?? $this->file_path ?? null;
        }

        // If still empty, return default
        if (! $imagePath) {
            return asset('images/default-product.png');
        }

        if (! is_string($imagePath)) {
            return asset('images/default-product.png');
        }

        // allow absolute URLs from seeders (picsum etc.)
        if (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
            return $imagePath;
        }

        $imagePath = ltrim($imagePath, '/');

        if (str_starts_with($imagePath, 'storage/')) {
            return asset($imagePath);
        }

        if (Storage::disk('public_app_public')->exists($imagePath)) {
            $base = rtrim((string) config('filesystems.disks.public_app_public.url', rtrim((string) config('app.url', ''), '/').'/storage/app/public'), '/');
            return $base . '/' . ltrim($imagePath, '/');
        }

        if (Storage::disk('public')->exists($imagePath)) {
            $base = rtrim((string) config('filesystems.disks.public.url', rtrim((string) config('app.url', ''), '/').'/storage'), '/');
            return $base . '/' . ltrim($imagePath, '/');
        }

        // Some deployments may store files directly under public/storage/app/public
        if (file_exists(public_path('storage/app/public/' . $imagePath))) {
            return asset('storage/app/public/' . ltrim($imagePath, '/'));
        }

        if (file_exists(public_path($imagePath))) {
            return asset($imagePath);
        }

        // Fallback to storage path (may still work if webserver is configured differently)
        return asset('storage/' . ltrim($imagePath, '/'));
    }

    public function getFormattedPriceAttribute(): string {
        return 'Rp ' . number_format((float) $this->price, 0, ',', '.');
    }

    public function getSoldCountAttribute(): int
    {
        return (int) ($this->statistics?->sold_count ?? 0);
    }

    public function getRatingAttribute(): float
    {
        return (float) ($this->statistics?->rating_average ?? 0);
    }

    public function getRatingAverageAttribute(): float
    {
        return (float) ($this->statistics?->rating_average ?? 0);
    }

    public function getReviewCountAttribute(): int
    {
        return (int) ($this->statistics?->review_count ?? 0);
    }

    public function getViewsCountAttribute(): int
    {
        return (int) ($this->statistics?->views_count ?? 0);
    }

    public function getDownloadsCountAttribute(): int
    {
        return (int) ($this->statistics?->downloads_count ?? 0);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'published' => 'Published',
            'archived' => 'Archived',
            default => $this->status,
        };
    }

    public function updateRating(): void {
        $avg = $this->reviews()->avg('rating') ?? 0;
        $count = $this->reviews()->count();

        $this->statistics()->updateOrCreate([], [
            'rating_average' => round($avg, 2),
            'review_count' => $count,
        ]);
    }
}