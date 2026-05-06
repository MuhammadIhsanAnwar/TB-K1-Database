<?php

namespace Database\Seeders;

use App\Models\Buyer;
use Illuminate\Database\Seeder;

class BuyerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 450 verified buyers (random data)
        Buyer::factory(450)->create();

        // Create 50 unverified buyers (pending email verification)
        Buyer::factory(50)->unverified()->create();

        // Create some sample buyers with specific data
        Buyer::create([
            'name' => 'Ahmad Pembeli',
            'username' => 'ahmad_pembeli',
            'email' => 'ahmad@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('Password123!'),
            'phone' => '081234567890',
            'avatar' => null,
            'status' => 'active',
        ]);

        Buyer::create([
            'name' => 'Siti Pembeli',
            'username' => 'siti_pembeli',
            'email' => 'siti@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('Password123!'),
            'phone' => '081234567891',
            'avatar' => null,
            'status' => 'active',
        ]);

        Buyer::create([
            'name' => 'Budi Pembeli',
            'username' => 'budi_pembeli',
            'email' => 'budi@example.com',
            'email_verified_at' => null,
            'password' => bcrypt('Password123!'),
            'phone' => '081234567892',
            'avatar' => null,
            'status' => 'active',
        ]);
    }
}
