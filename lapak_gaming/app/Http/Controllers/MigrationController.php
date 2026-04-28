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
            $errorMsg = $e->getMessage();
            
            // Handle duplicate column error (partial migrate)
            if (strpos($errorMsg, 'Duplicate column') !== false || 
                strpos($errorMsg, '1060') !== false) {
                
                try {
                    // Try migrate again - migrasi yang diupdate sudah handle duplicate columns
                    Artisan::call('migrate', ['--force' => true]);
                    $output = Artisan::output();

                    return response()->json([
                        'success' => true,
                        'message' => '✅ Database sudah dibuat (kolom duplikat ditangani)!',
                        'output' => $output,
                        'redirect' => route('setup.index')
                    ]);
                } catch (\Exception $retryError) {
                    return response()->json([
                        'success' => false,
                        'message' => '❌ Migrasi masih gagal. Coba reset database.',
                        'output' => $retryError->getMessage(),
                        'canReset' => true
                    ], 400);
                }
            }
            
            // Handle foreign key constraint error
            if (strpos($errorMsg, 'Foreign key constraint') !== false || 
                strpos($errorMsg, 'errno: 150') !== false) {
                
                try {
                    // Reset dan jalankan ulang
                    Artisan::call('migrate:reset', ['--force' => true]);
                    Artisan::call('migrate', ['--force' => true]);
                    $output = Artisan::output();

                    return response()->json([
                        'success' => true,
                        'message' => '✅ Migrasi berhasil setelah reset database!',
                        'output' => $output,
                        'redirect' => route('setup.index')
                    ]);
                } catch (\Exception $resetError) {
                    return response()->json([
                        'success' => false,
                        'message' => '❌ Migrasi gagal: ' . $resetError->getMessage(),
                        'output' => $resetError->getMessage(),
                        'canReset' => true
                    ], 400);
                }
            }

            return response()->json([
                'success' => false,
                'message' => '❌ Migrasi gagal: ' . $errorMsg,
                'output' => $errorMsg
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
