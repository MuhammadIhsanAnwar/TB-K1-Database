<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SellerLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'minimum_orders',
        'minimum_revenue',
        'fee_percent',
        'badge_color',
        'auto_approve',
    ];

    protected $casts = [
        'minimum_revenue' => 'decimal:2',
        'fee_percent' => 'decimal:2',
        'auto_approve' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function benefitItems(): HasMany
    {
        return $this->hasMany(SellerLevelBenefit::class)->orderBy('sort_order');
    }

    public function getBenefitsAttribute(): array
    {
        return $this->relationLoaded('benefitItems')
            ? $this->benefitItems->pluck('benefit')->all()
            : $this->benefitItems()->pluck('benefit')->all();
    }
}