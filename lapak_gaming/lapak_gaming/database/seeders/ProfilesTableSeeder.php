<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProfile;

class ProfilesTableSeeder extends Seeder
{
    public function run(): void
    {
        // Populate user_profiles for buyers (users without profiles)
        User::where('role', 'buyer')
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

        // Populate user_profiles for sellers (users without profiles)
        User::where('role', 'seller')
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

        $this->command->info('Profiles seeding completed!');
    }
}
