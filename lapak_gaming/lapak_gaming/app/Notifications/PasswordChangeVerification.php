<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChangeVerification extends Notification
{
    use Queueable;

    protected string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Kode Verifikasi Ubah Password')
            ->line('Anda meminta perubahan password akun.')
            ->line('Gunakan kode verifikasi berikut untuk melanjutkan:')
            ->line('Kode: ' . $this->code)
            ->line('Kode ini akan kedaluwarsa dalam 10 menit.')
            ->line('Jika Anda tidak meminta perubahan password, abaikan email ini.');
    }
}