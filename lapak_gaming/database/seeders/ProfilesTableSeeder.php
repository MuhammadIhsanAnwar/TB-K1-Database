<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserAddress;

class ProfilesTableSeeder extends Seeder
{
    public function run(): void
    {
        // Populate user_profiles for buyers (users without profiles)
        $buyers = User::where('role', 'buyer')
            ->whereDoesntHave('profile')
            ->chunk(100, function ($users) {
                foreach ($users as $user) {
                    UserProfile::create([
                        'user_id' => $user->id,
                        'gender' => collect(['male', 'female', 'other'])->random(),
                        'birth_date' => fake()->dateTimeBetween('-60 years', '-18 years'),
                        'phone' => fake()->phoneNumber(),
                        'avatar_path' => 'user-avatars/default.png',
                    ]);
                }
            });

        // Populate user_addresses for buyers (users without addresses)
        $buyersWithoutAddress = User::where('role', 'buyer')
            ->whereDoesntHave('address')
            ->chunk(100, function ($users) {
                foreach ($users as $user) {
                    UserAddress::create([
                        'user_id' => $user->id,
                        'province' => fake()->state(),
                        'regency' => fake()->city(),
                        'district' => fake()->lastName(),
                        'village' => fake()->lastName(),
                        'postal_code' => fake()->postcode(),
                        'full_address' => fake()->address(),
                    ]);
                }
            });

        // Populate user_profiles for sellers (users without profiles)
        $sellers = User::where('role', 'seller')
            ->whereDoesntHave('profile')
            ->chunk(100, function ($users) {
                foreach ($users as $user) {
                    UserProfile::create([
                        'user_id' => $user->id,
                        'gender' => collect(['male', 'female', 'other'])->random(),
                        'birth_date' => fake()->dateTimeBetween('-60 years', '-18 years'),
                        'phone' => fake()->phoneNumber(),
                        'avatar_path' => 'seller-logos/default.png',
                        'bio' => fake()->sentence(),
                    ]);
                }
            });

        // Populate user_addresses for sellers (users without addresses)
        $sellersWithoutAddress = User::where('role', 'seller')
            ->whereDoesntHave('address')
            ->chunk(100, function ($users) {
                foreach ($users as $user) {
                    UserAddress::create([
                        'user_id' => $user->id,
                        'province' => fake()->state(),
                        'regency' => fake()->city(),
                        'district' => fake()->lastName(),
                        'village' => fake()->lastName(),
                        'postal_code' => fake()->postcode(),
                        'full_address' => fake()->address(),
                    ]);
                }
            });

        $this->command->info('Profiles and addresses seeding completed!');
    }
}
