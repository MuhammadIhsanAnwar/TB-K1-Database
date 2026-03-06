<?php

namespace Database\Seeders;

use App\Models\SellerLevel;
use Illuminate\Database\Seeder;

class SellerLevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            [
                'name' => 'Regular',
                'min_sales' => 0,
                'min_rating' => 0,
                'commission_rate' => 15.00,
                'benefits' => 'Basic seller features',
                'badge_color' => '#6366f1',
            ],
            [
                'name' => 'Gold',
                'min_sales' => 50,
                'min_rating' => 4.0,
                'commission_rate' => 12.00,
                'benefits' => 'Featured products, priority support',
                'badge_color' => '#fbbf24',
            ],
            [
                'name' => 'Platinum',
                'min_sales' => 200,
                'min_rating' => 4.5,
                'commission_rate' => 10.00,
                'benefits' => 'Exclusive badge, analytics, highest priority',
                'badge_color' => '#e5e7eb',
            ],
        ];

        foreach ($levels as $level) {
            SellerLevel::create($level);
        }
    }
}
