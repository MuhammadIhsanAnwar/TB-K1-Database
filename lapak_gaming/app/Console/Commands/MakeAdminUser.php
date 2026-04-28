<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class MakeAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin-user';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Buat user admin baru untuk akses terminal dan dashboard admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Membuat User Admin ===');

        // Tanya input kepada user
        $name = $this->ask('Nama admin (default: Marketplace Admin)', 'Marketplace Admin');
        $email = $this->ask('Email admin (default: admin@marketplace.test)', 'admin@marketplace.test');
        $password = $this->secret('Password (hidden input)');

        // Validasi
        if (empty($password)) {
            $this->error('❌ Password tidak boleh kosong!');
            return 1;
        }

        if (strlen($password) < 6) {
            $this->error('❌ Password minimal 6 karakter!');
            return 1;
        }

        // Cek apakah email sudah ada
        if (User::where('email', $email)->exists()) {
            $this->warn('⚠️  Email sudah terdaftar. Mengupdate user existing...');
            $user = User::where('email', $email)->first();
            $user->update([
                'name' => $name,
                'password' => Hash::make($password),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
            $this->info('✅ User admin berhasil diupdate!');
        } else {
            // Buat user baru
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'admin',
                'email_verified_at' => now(),
                'status' => 'active',
            ]);
            $this->info('✅ User admin berhasil dibuat!');
        }

        // Tampilkan info login
        $this->newLine();
        $this->info('📋 Informasi Login Terminal Web:');
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->line("📧 Email: {$email}");
        $this->line("🔐 Password: {$password}");
        $this->line("🔗 URL Terminal: https://lapakgaming.neoverse.my.id/admin/terminal");
        $this->line("🔗 URL Dashboard: https://lapakgaming.neoverse.my.id/admin/dashboard");
        $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->newLine();

        return 0;
    }
}
