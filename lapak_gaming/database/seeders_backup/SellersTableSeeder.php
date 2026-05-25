<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class SellersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Create 1000 sellers if they don't exist
        $existingSellerCount = User::where('role', 'seller')->count();
        $sellersNeeded = 1000 - $existingSellerCount;
        
        if ($sellersNeeded > 0) {
            User::factory()->count($sellersNeeded)->seller()->create()->each(function (User $user): void {
                $shopName = $this->generateShopName($user->name);

                $user->forceFill([
                    'shop_name' => $shopName,
                    'shop_description' => fake()->sentence(12),
                    'seller_status' => 'approved',
                    'avatar' => $this->avatarUrl($shopName),
                    'shop_photo' => $this->avatarUrl($shopName),
                ])->save();
            });
            $this->command->info("Created $sellersNeeded sellers");
        } else {
            $this->command->info("Database already has 1000+ sellers, skipping...");
        }

        User::where('role', 'seller')
            ->where(function ($query): void {
                $query->whereNull('avatar')->orWhere('avatar', '');
            })
            ->get()
            ->each(function (User $user): void {
                $shopName = $this->generateShopName($user->shop_name ?: $user->name);

                $user->forceFill([
                    'shop_name' => $user->shop_name ?: $shopName,
                    'avatar' => $this->avatarUrl($shopName),
                    'shop_photo' => $this->avatarUrl($shopName),
                ])->save();
            });
    }

    private function generateShopName(string $name): string
    {
        return Str::of($name)->trim()->whenEmpty(fn () => 'Seller Demo')->append(' Store')->toString();
    }

    private function avatarUrl(string $name): string
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($name ?: 'Seller') . '&background=111827&color=ffffff&bold=true&rounded=true';
    }
}
