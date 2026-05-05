<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Top Up Game', 'Game Key', 'Roblox Games', 'Akun', 'Voucher',
            'Koin Game', 'Item', 'Joki', 'Top Up Login', 'Streaming',
            'Live Show', 'Pulsa dan Utilitas', 'Aplikasi dan Software', 'Steam Voucher',
            'Growtopia DL', 'Steam Key', 'Fish It', 'Mobile Legends WDP', 'Free Fire Diamonds'
        ];

        $unique = array_values(array_unique($names));

        foreach ($unique as $i => $name) {
            Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'sort_order' => $i]
            );
        }
    }
}
