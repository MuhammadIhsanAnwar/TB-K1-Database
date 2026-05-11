<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Create 600 buyer users if they don't exist
        $existingBuyerCount = DB::table('users')
            ->where('role', 'buyer')
            ->count();
        $buyersNeeded = 600 - $existingBuyerCount;
        
        if ($buyersNeeded > 0) {
            User::factory()->count($buyersNeeded)->create();
            $this->command->info("Created $buyersNeeded buyers");
        } else {
            $this->command->info("Database already has 600+ buyers, skipping...");
        }
    }
}
