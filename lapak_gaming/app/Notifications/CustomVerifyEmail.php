<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

class CustomVerifyEmail extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    protected function verificationUrl($notifiable)
    {
        $expiration = Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60));

        return URL::temporarySignedRoute(
            'activation.activate',
            $expiration,
            ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
        );
    }

    public function toMail($notifiable)
    {
        $url = $this->verificationUrl($notifiable);
        $appName = (string) Config::get('app.name', 'Lapak Gaming');
        $logoUrl = url('storage/app/public/logo/logo.png');

        return (new MailMessage)
            ->subject('Aktivasi Akun Anda')
            ->markdown('emails.verify-email', [
                'url' => $url,
                'recipientName' => $notifiable->name ?? $notifiable->email,
                'appName' => $appName,
                'logoUrl' => $logoUrl,
            ]);
    }
}
