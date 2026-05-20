@component('mail::message')
<div style="text-align: center; margin-bottom: 16px;">
    <img src="{{ $logoUrl }}" alt="{{ $appName }} logo" style="width: 72px; height: 72px; border-radius: 12px; object-fit: contain;">
</div>

# Halo {{ $recipientName }}

Terima kasih telah mendaftar di {{ $appName }}.
Silakan klik tombol di bawah untuk mengaktifkan akun Anda dan menyelesaikan pendaftaran.

@component('mail::button', ['url' => $url])
Aktivasi Akun
@endcomponent

Jika Anda tidak merasa melakukan pendaftaran ini, abaikan email ini.

Salam,<br>
Tim {{ $appName }}
@endcomponent
