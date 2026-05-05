<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sold_count',
        'rating_average',
        'review_count',
        'views_count',
        'downloads_count',
    ];

    protected function casts(): array
    {
        return [
            'sold_count' => 'integer',
            'rating_average' => 'decimal:2',
            'review_count' => 'integer',
            'views_count' => 'integer',
            'downloads_count' => 'integer',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
