<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class MigrationController extends Controller
{
    /**
     * Tampilkan halaman migrasi
     */
    public function index()
    {
        return view('setup.migration');
    }

    /**
     * Jalankan migrasi via AJAX
     */
    public function run()
    {
        try {
            // Jalankan migrasi
            Artisan::call('migrate', ['--force' => true]);
            $output = Artisan::output();

            return response()->json([
                'success' => true,
                'message' => '✅ Migrasi database berhasil!',
                'output' => $output,
                'redirect' => route('setup.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Migrasi gagal: ' . $e->getMessage(),
                'output' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Cek status migrasi
     */
    public function status()
    {
        $tableExists = Schema::hasTable('users');

        return response()->json([
            'tables_exist' => $tableExists,
            'message' => $tableExists ? 'Tabel sudah ada' : 'Tabel belum dibuat'
        ]);
    }
}
