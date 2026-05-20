<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class SellersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Create 15 sellers
        User::factory()->count(15)->seller()->create();
        $this->command->info("Created 15 sellers");
    }
}
