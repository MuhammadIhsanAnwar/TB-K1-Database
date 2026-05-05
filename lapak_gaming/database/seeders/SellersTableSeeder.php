<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class SellersTableSeeder extends Seeder
{
    public function run(): void
    {
        // create 600 sellers
        User::factory()->count(600)->seller()->create();
    }
}
