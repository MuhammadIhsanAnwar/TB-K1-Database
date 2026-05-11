<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SellersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Create 600 sellers if they don't exist
        $existingSellerCount = DB::table('users')
            ->where('role', 'seller')
            ->count();
        $sellersNeeded = 600 - $existingSellerCount;
        
        if ($sellersNeeded > 0) {
            User::factory()->count($sellersNeeded)->seller()->create();
            $this->command->info("Created $sellersNeeded sellers");
        } else {
            $this->command->info("Database already has 600+ sellers, skipping...");
        }
    }
}
