<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Create 1000 buyer users if they don't exist
        $existingBuyerCount = User::where('role', 'buyer')->count();
        $buyersNeeded = 1000 - $existingBuyerCount;
        
        if ($buyersNeeded > 0) {
            $buyers = User::factory()->count($buyersNeeded)->create();

            /** @var \App\Models\User $user */
            foreach ($buyers as $user) {
                $user->forceFill([
                    'avatar' => $this->avatarUrl($user->name),
                ])->save();
            }
            $this->command->info("Created $buyersNeeded buyers");
        } else {
            $this->command->info("Database already has 1000+ buyers, skipping...");
        }

        User::where('role', 'buyer')
            ->where(function ($query): void {
                $query->whereNull('avatar')->orWhere('avatar', '');
            })
            ->chunkById(200, function ($users): void {
                /** @var \App\Models\User $user */
                foreach ($users as $user) {
                    $user->forceFill([
                        'avatar' => $this->avatarUrl($user->name),
                    ])->save();
                }
            });
    }

    private function avatarUrl(string $name): string
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($name ?: 'Buyer') . '&background=0f172a&color=ffffff&bold=true&rounded=true';
    }
}
