<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'administrator@lapakgaming.neoverse.my.id'],
            [
                'name' => 'Administrator',
                'email_verified_at' => now(),
                'password' => Hash::make('lapakgaming'),
                'role' => 'admin',
                'status' => 'active',
                'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode('Administrator') . '&background=111827&color=ffffff&bold=true&rounded=true',
            ]
        );
    }
}
