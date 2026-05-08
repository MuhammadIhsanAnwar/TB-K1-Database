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

        return (new MailMessage)
            ->subject('Aktivasi Akun Anda')
            ->greeting('Halo ' . ($notifiable->name ?? $notifiable->email))
            ->line('Terima kasih telah mendaftar. Silakan klik tombol di bawah untuk mengaktifkan akun Anda dan menyelesaikan pendaftaran.')
            ->action('Aktivasi Akun', $url)
            ->line('Jika Anda tidak merasa melakukan pendaftaran ini, abaikan email ini.')
            ->salutation('Salam, Tim ' . Config::get('app.name'));
    }
}
