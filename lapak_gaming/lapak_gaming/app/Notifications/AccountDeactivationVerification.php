<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountDeactivationVerification extends Notification
{
    use Queueable;

    public function __construct(private readonly string $code)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Kode Verifikasi Nonaktifkan Akun')
            ->line('Anda meminta untuk menonaktifkan akun sementara.')
            ->line('Gunakan kode verifikasi berikut untuk melanjutkan proses nonaktif akun:')
            ->line('Kode: ' . $this->code)
            ->line('Kode ini akan kedaluwarsa dalam 30 menit.')
            ->line('Jika Anda tidak meminta tindakan ini, abaikan email ini.');
    }
}
