<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\DifferentDeviceLoginNotification;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class SendDifferentDeviceLoginNotification
{
    public function handle(Login $event): void
    {
        if (app()->runningInConsole()) {
            return;
        }

        $request = request();

        if (! $request instanceof Request) {
            return;
        }

        $user = $event->user;

        if (! $user instanceof User) {
            return;
        }

        $currentUserAgent = trim((string) $request->userAgent());
        $currentFingerprint = $this->fingerprint($currentUserAgent);
        $storedFingerprint = (string) ($user->last_login_device_hash ?? '');
        $isDifferentDevice = $storedFingerprint !== '' && ! hash_equals($storedFingerprint, $currentFingerprint);

        if ($isDifferentDevice) {
            try {
                $user->notify(new DifferentDeviceLoginNotification(
                    previousDevice: $user->last_login_user_agent,
                    currentDevice: $currentUserAgent ?: null,
                    ipAddress: $request->ip(),
                    loggedInAt: Carbon::now(),
                ));
            } catch (\Throwable $exception) {
                Log::warning('Different-device login notification failed', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'message' => $exception->getMessage(),
                ]);
            }
        }

        $user->forceFill([
            'last_login_at' => Carbon::now(),
            'last_login_ip' => $request->ip(),
            'last_login_user_agent' => $currentUserAgent ?: null,
            'last_login_device_hash' => $currentFingerprint,
        ])->save();
    }

    private function fingerprint(string $userAgent): string
    {
        return hash('sha256', strtolower(trim($userAgent)));
    }
}