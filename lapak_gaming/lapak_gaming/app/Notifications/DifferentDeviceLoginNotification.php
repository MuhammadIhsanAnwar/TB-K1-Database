<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class DifferentDeviceLoginNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly ?string $previousDevice,
        private readonly ?string $currentDevice,
        private readonly ?string $ipAddress,
        private readonly Carbon $loggedInAt,
    ) {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $appName = config('app.name', 'Lapak Gaming');
        $loggedInAtWib = $this->loggedInAt->copy()->setTimezone('Asia/Jakarta');

        return (new MailMessage)
            ->subject('Login baru terdeteksi di perangkat lain')
            ->greeting('Halo ' . ($notifiable->name ?? $notifiable->email) . ',')
            ->line('Kami mendeteksi login ke akun Anda dari perangkat atau browser yang berbeda.')
            ->line('Waktu login: ' . $loggedInAtWib->format('d M Y H:i:s') . ' WIB')
            ->line('Perangkat sebelumnya: ' . ($this->previousDevice ?: 'Tidak diketahui'))
            ->line('Perangkat saat ini: ' . ($this->currentDevice ?: 'Tidak diketahui'))
            ->line('IP saat ini: ' . ($this->ipAddress ?: 'Tidak diketahui'))
            ->line('Jika ini bukan Anda, segera ubah password dan amankan akun Anda.')
            ->action('Ubah Password', route('password.request'))
            ->salutation('Tim ' . $appName);
    }
}