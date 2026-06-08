<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'option1_value',
        'option1_price',
        'option2_value',
        'option2_price',
        'payment_text',
        'region_text',
        'promo_badge',
        'image_path',
    ];
}
