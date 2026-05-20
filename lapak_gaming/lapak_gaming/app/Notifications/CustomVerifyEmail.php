<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class CustomVerifyEmail extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    protected function verificationUrl($notifiable)
    {
        try {
            $expiration = Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60));

            return URL::temporarySignedRoute(
                'activation.activate',
                $expiration,
                ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
            );
        } catch (\Throwable $exception) {
            Log::error('Failed to generate verification URL', [
                'user_id' => $notifiable->getKey() ?? null,
                'message' => $exception->getMessage(),
            ]);
            throw $exception;
        }
    }

    public function toMail($notifiable)
    {
        try {
            $url = $this->verificationUrl($notifiable);
            $appName = (string) Config::get('app.name', 'Lapak Gaming');
            $logoUrl = asset('storage/app/public/logo/logo.png');

            $mailMessage = (new MailMessage)
                ->subject('Aktivasi Akun Anda')
                ->markdown('emails.verify-email', [
                    'url' => $url,
                    'recipientName' => $notifiable->name ?? $notifiable->email,
                    'appName' => $appName,
                    'logoUrl' => $logoUrl,
                ]);

            // Use the failover mailer if configured
            if (Config::has('mail.mailers.failover')) {
                $mailMessage->mailer('failover');
            }

            return $mailMessage;
        } catch (\Throwable $exception) {
            Log::error('Failed to generate verification email', [
                'user_id' => $notifiable->getKey() ?? null,
                'email' => $notifiable->email ?? null,
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);
            throw $exception;
        }
    }
}
