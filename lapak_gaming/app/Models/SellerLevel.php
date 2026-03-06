<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellerLevel extends Model
{
    use HasFactory;

    protected $table = 'seller_levels';

    protected $fillable = [
        'name',
        'min_sales',
        'min_rating',
        'commission_rate',
        'benefits',
        'badge_color',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
    ];

    public function sellerAccounts()
    {
        return $this->hasMany(SellerAccount::class);
    }
}
