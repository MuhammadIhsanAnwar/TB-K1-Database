<?php

namespace Database\Seeders;

use App\Models\SellerLevel;
use Illuminate\Database\Seeder;

class SellerLevelSeeder extends Seeder
{
    public function run(): void
    {
        SellerLevel::insert([
            [
                'name' => 'Starter',
                'minimum_orders' => 0,
                'minimum_revenue' => 0,
                'fee_percent' => 7,
                'badge_color' => 'slate',
                'benefits' => json_encode(['Dashboard dasar', 'Auto delivery']),
                'auto_approve' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pro',
                'minimum_orders' => 50,
                'minimum_revenue' => 500000,
                'fee_percent' => 5,
                'badge_color' => 'emerald',
                'benefits' => json_encode(['Statistik lanjutan', 'Prioritas moderasi']),
                'auto_approve' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Elite',
                'minimum_orders' => 250,
                'minimum_revenue' => 5000000,
                'fee_percent' => 3,
                'badge_color' => 'amber',
                'benefits' => json_encode(['Fee lebih rendah', 'Seller badge premium']),
                'auto_approve' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}