<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS di production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Hanya share categories jika table sudah ada
        if (Schema::hasTable('categories')) {
            try {
                View::share('categories', Category::all());
            } catch (\Exception $e) {
                View::share('categories', collect());
            }
        } else {
            View::share('categories', collect());
        }
    }
}
