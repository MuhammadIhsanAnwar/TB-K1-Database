<?php

namespace App\Services;

use RuntimeException;
use Twilio\Rest\Client;

class TwilioSmsService
{
    public function sendVerificationCode(string $phoneNumber, string $code): void
    {
        $accountSid = (string) config('services.twilio.sid');
        $authToken = (string) config('services.twilio.token');
        $fromNumber = (string) config('services.twilio.from');

        if ($accountSid === '' || $authToken === '' || $fromNumber === '') {
            throw new RuntimeException('Konfigurasi Twilio belum lengkap.');
        }

        $client = new Client($accountSid, $authToken);

        $client->messages->create($phoneNumber, [
            'from' => $fromNumber,
            'body' => 'Kode verifikasi Lapak Gaming Anda: ' . $code . '. Kode ini berlaku selama 10 menit.',
        ]);
    }
}