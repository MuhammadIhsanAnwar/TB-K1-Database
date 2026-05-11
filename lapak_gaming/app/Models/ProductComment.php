<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'user_id',
        'parent_comment_id',
        'content',
        'rating',
        'is_verified_buyer',
        'likes_count',
        'replies_count',
        'status',
    ];

    protected $casts = [
        'is_verified_buyer' => 'boolean',
        'likes_count' => 'integer',
        'replies_count' => 'integer',
    ];

    // Relations
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parentComment(): BelongsTo
    {
        return $this->belongsTo(ProductComment::class, 'parent_comment_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(ProductComment::class, 'parent_comment_id')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(CommentLike::class, 'product_comment_id');
    }

    public function userLikes()
    {
        $userId = auth()->id();
        if (!$userId) {
            return $this->likes()->whereRaw('1=0'); // Return empty query
        }
        return $this->likes()->where('user_id', $userId);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeMainComments($query)
    {
        return $query->whereNull('parent_comment_id');
    }

    public function scopeVerifiedBuyers($query)
    {
        return $query->where('is_verified_buyer', true);
    }

    public function scopeWithRating($query)
    {
        return $query->whereNotNull('rating');
    }

    // Helpers
    public function isLikedByUser($userId = null): bool
    {
        $userId = $userId ?? auth()->id();
        if (!$userId) {
            return false;
        }
        return $this->likes()->where('user_id', $userId)->exists();
    }

    public function toggleLike($userId = null): bool
    {
        $userId = $userId ?? auth()->id();

        if ($this->isLikedByUser($userId)) {
            $this->likes()->where('user_id', $userId)->delete();
            $this->decrement('likes_count');
            return false;
        } else {
            $this->likes()->create([
                'product_comment_id' => $this->id,
                'user_id' => $userId,
            ]);
            $this->increment('likes_count');
            return true;
        }
    }

    public function isSellerReply(): bool
    {
        return $this->user_id === $this->product->seller_id;
    }
}
