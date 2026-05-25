@extends('mail::message')

# Balasan Pesan dari {{ $appName }}

Halo {{ $contactMessage->name }},

Terima kasih sudah menghubungi kami. Berikut balasan dari tim admin:

@component('mail::panel')
{{ $contactMessage->admin_reply }}
@endcomponent

Subjek pesan Anda: {{ $contactMessage->subject }}

Jika masih ada kendala, Anda bisa membalas email ini atau mengirim pesan baru melalui halaman Hubungi Kami.

Salam,
{{ $appName }}