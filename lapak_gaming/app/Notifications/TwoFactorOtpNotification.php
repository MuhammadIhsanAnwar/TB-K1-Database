<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TwoFactorOtpNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $code,
        private readonly string $method,
    ) {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Kode Verifikasi 2 Langkah')
            ->line('Gunakan kode berikut untuk menyelesaikan login akun Anda.')
            ->line('Metode: ' . strtoupper($this->method))
            ->line('Kode: ' . $this->code)
            ->line('Kode ini berlaku selama 10 menit.');
    }
}