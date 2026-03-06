<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellerAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'seller_accounts';

    protected $fillable = [
        'user_id',
        'shop_name',
        'shop_description',
        'shop_avatar',
        'shop_banner',
        'address',
        'city',
        'province',
        'seller_level_id',
        'total_sales',
        'rating',
        'total_reviews',
        'is_verified',
        'verified_at',
        'is_banned',
        'banned_at',
        'ban_reason',
    ];

    protected $casts = [
        'rating' => 'decimal:2',
        'is_verified' => 'boolean',
        'is_banned' => 'boolean',
        'verified_at' => 'datetime',
        'banned_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(SellerLevel::class, 'seller_level_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'seller_id', 'user_id');
    }
}
