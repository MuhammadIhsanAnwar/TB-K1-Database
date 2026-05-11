<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AccountDeletionVerification extends Notification
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
            ->subject('Kode Verifikasi Hapus Akun')
            ->line('Anda telah meminta untuk menghapus akun Anda secara permanen.')
            ->line('Gunakan kode verifikasi di bawah ini untuk melanjutkan proses penghapusan akun:')
            ->line('Kode: ' . $this->code)
            ->line('Kode ini akan kedaluwarsa dalam 30 menit.')
            ->line('Jika Anda tidak meminta penghapusan akun, abaikan email ini.');
    }
}
