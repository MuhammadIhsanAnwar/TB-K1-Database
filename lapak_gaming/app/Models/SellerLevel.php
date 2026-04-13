<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'minimum_orders',
        'minimum_revenue',
        'fee_percent',
        'badge_color',
        'benefits',
        'auto_approve',
    ];

    protected $casts = [
        'minimum_revenue' => 'decimal:2',
        'fee_percent' => 'decimal:2',
        'benefits' => 'array',
        'auto_approve' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}