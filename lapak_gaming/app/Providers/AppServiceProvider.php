<?php

namespace App\Providers;

use App\Listeners\SendDifferentDeviceLoginNotification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Events\Login;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Event;
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

        VerifyEmail::createUrlUsing(function ($notifiable): string {
            return URL::temporarySignedRoute(
                'activation.activate',
                now()->addMinutes(config('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );
        });

        VerifyEmail::toMailUsing(function ($notifiable, string $url): MailMessage {
            return (new MailMessage)
                ->subject('Aktivasi Akun Anda')
                ->markdown('emails.verify-email', [
                    'url' => $url,
                    'recipientName' => $notifiable->name ?? $notifiable->email,
                    'appName' => config('app.name', 'Lapak Gaming'),
                    'logoUrl' => asset('storage/app/public/logo/logo.png'),
                ]);
        });

        Event::listen(Login::class, SendDifferentDeviceLoginNotification::class);

        if ($this->app->runningInConsole()) {
            View::share('categories', collect());

            return;
        }

        // Hanya share categories jika table sudah ada
        if (Schema::hasTable('categories')) {
            try {
                View::share('categories', Category::query()
                    ->active()
                    ->whereNull('parent_id')
                    ->orderBy('sort_order')
                    ->take(13)
                    ->get());
            } catch (\Exception $e) {
                View::share('categories', collect());
            }
        } else {
            View::share('categories', collect());
        }
    }
}
