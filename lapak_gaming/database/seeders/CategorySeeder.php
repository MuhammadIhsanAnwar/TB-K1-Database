<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $gaming = Category::create([
            'name' => 'Gaming',
            'slug' => 'gaming',
            'description' => 'Item digital untuk game populer.',
            'sort_order' => 1,
        ]);

        Category::create([
            'parent_id' => $gaming->id,
            'name' => 'Top Up',
            'slug' => 'top-up',
            'description' => 'Voucher dan saldo game.',
            'sort_order' => 1,
        ]);

        Category::create([
            'parent_id' => $gaming->id,
            'name' => 'Account',
            'slug' => 'account',
            'description' => 'Akun game siap pakai.',
            'sort_order' => 2,
        ]);

        Category::create([
            'name' => 'Software',
            'slug' => 'software',
            'description' => 'Lisensi digital dan tools produktivitas.',
            'sort_order' => 2,
        ]);
    }
}