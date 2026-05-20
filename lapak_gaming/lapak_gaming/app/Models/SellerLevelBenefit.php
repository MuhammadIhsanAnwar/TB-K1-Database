<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerLevelBenefit extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_level_id',
        'sort_order',
        'benefit',
    ];

    public function sellerLevel()
    {
        return $this->belongsTo(SellerLevel::class);
    }
}
