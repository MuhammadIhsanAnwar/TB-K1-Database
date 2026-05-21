<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Str;

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
                        'avatar_path' => $this->avatarUrl($user->name),
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
                        'avatar_path' => $this->avatarUrl($user->name),
                        'bio' => fake()->sentence(),
                    ]);
                }
            });

        User::where(function ($query): void {
                $query->whereNull('avatar')->orWhere('avatar', '');
            })
            ->chunkById(200, function ($users): void {
                foreach ($users as $user) {
                    $user->forceFill([
                        'avatar' => $this->avatarUrl($user->name ?: $user->email),
                    ])->save();
                }
            });

        $this->command->info('Profiles seeding completed!');
    }

    private function avatarUrl(string $name): string
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($name ?: 'User') . '&background=1f2937&color=ffffff&bold=true&rounded=true';
    }
}
