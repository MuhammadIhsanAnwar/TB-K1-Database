<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class SeederController extends Controller
{
    /**
     * Clear all products and re-seed them
     */
    public function reseedProducts(Request $request): RedirectResponse
    {
        // Security: require token or specific query param
        if ($request->query('token') !== config('app.seeder_token') && !auth()->check()) {
            return redirect('/')->with('error', 'Unauthorized');
        }

        try {
            DB::beginTransaction();

            // Clear existing products
            Product::query()->delete();

            // Call the seeder
            \Illuminate\Support\Facades\Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\ProductsTableSeeder',
                '--force' => true,
            ]);

            DB::commit();

            return redirect('/')->with('success', 'Products re-seeded successfully! ' . Product::count() . ' products created.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/')->with('error', 'Seeding failed: ' . $e->getMessage());
        }
    }

    /**
     * Update product statuses to published
     */
    public function publishProducts(Request $request): RedirectResponse
    {
        if ($request->query('token') !== config('app.seeder_token') && !auth()->check()) {
            return redirect('/')->with('error', 'Unauthorized');
        }

        try {
            $updated = Product::query()->update(['status' => 'published']);
            return redirect('/')->with('success', "$updated products updated to published status.");
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Update failed: ' . $e->getMessage());
        }
    }
}
