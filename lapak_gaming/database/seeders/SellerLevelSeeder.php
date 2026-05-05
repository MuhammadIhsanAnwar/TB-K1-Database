<?php

namespace Database\Seeders;

use App\Models\SellerLevel;
use App\Models\SellerLevelBenefit;
use Illuminate\Database\Seeder;

class SellerLevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            [
                'name' => 'Starter',
                'minimum_orders' => 0,
                'minimum_revenue' => 0,
                'fee_percent' => 7,
                'badge_color' => 'slate',
                'auto_approve' => false,
                'benefits' => ['Dashboard dasar', 'Auto delivery'],
            ],
            [
                'name' => 'Pro',
                'minimum_orders' => 50,
                'minimum_revenue' => 500000,
                'fee_percent' => 5,
                'badge_color' => 'emerald',
                'auto_approve' => true,
                'benefits' => ['Statistik lanjutan', 'Prioritas moderasi'],
            ],
            [
                'name' => 'Elite',
                'minimum_orders' => 250,
                'minimum_revenue' => 5000000,
                'fee_percent' => 3,
                'badge_color' => 'amber',
                'auto_approve' => true,
                'benefits' => ['Fee lebih rendah', 'Seller badge premium'],
            ],
        ];

        foreach ($levels as $levelData) {
            $benefits = $levelData['benefits'];
            unset($levelData['benefits']);

            $level = SellerLevel::updateOrCreate(
                ['name' => $levelData['name']],
                $levelData
            );

            foreach ($benefits as $index => $benefit) {
                SellerLevelBenefit::updateOrCreate([
                    'seller_level_id' => $level->id,
                    'sort_order' => $index,
                ], [
                    'benefit' => $benefit,
                ]);
            }

            // remove old benefit rows that are no longer defined
            SellerLevelBenefit::where('seller_level_id', $level->id)
                ->whereNotIn('sort_order', array_keys($benefits))
                ->delete();
        }
    }
}