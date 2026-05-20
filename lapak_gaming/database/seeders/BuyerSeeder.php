<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BuyerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample buyers with specific data (already covered by UsersTableSeeder)
        // This seeder can add premium or demo buyers if needed
        
        // Create some sample verified buyers with specific data
        User::firstOrCreate(
            ['email' => 'ahmad@example.com'],
            [
                'name' => 'Ahmad Pembeli',
                'email_verified_at' => now(),
                'password' => Hash::make('Password123!'),
                'phone' => '081234567890',
                'role' => 'buyer',
                'status' => 'active',
            ]
        );

        User::firstOrCreate(
            ['email' => 'siti@example.com'],
            [
                'name' => 'Siti Pembeli',
                'email_verified_at' => now(),
                'password' => Hash::make('Password123!'),
                'phone' => '081234567891',
                'role' => 'buyer',
                'status' => 'active',
            ]
        );

        User::firstOrCreate(
            ['email' => 'budi@example.com'],
            [
                'name' => 'Budi Pembeli',
                'email_verified_at' => null,
                'password' => Hash::make('Password123!'),
                'phone' => '081234567892',
                'role' => 'buyer',
                'status' => 'active',
            ]
        );

        $this->command->info('Sample buyers seeding completed!');
    }
}
