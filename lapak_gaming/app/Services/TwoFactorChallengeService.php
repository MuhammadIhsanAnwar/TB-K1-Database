<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\TwoFactorOtpNotification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;

class TwoFactorChallengeService
{
    public function __construct(
        private readonly TwilioSmsService $twilioSmsService,
        private readonly TwoFactorAuthenticationProvider $twoFactorProvider,
    ) {
    }

    public function requiresChallenge(User $user): bool
    {
        return (bool) $user->two_factor_enabled
            && (bool) $user->two_factor_confirmed_at
            && ! empty($this->enabledMethods($user));
    }

    public function enabledMethods(User $user): array
    {
        return array_values(array_unique(array_map('strval', $user->two_factor_methods ?: [])));
    }

    public function resolveLoginMethod(User $user): string
    {
        $methods = $this->enabledMethods($user);

        if (in_array('google', $methods, true) && ! empty($user->two_factor_google_secret)) {
            return 'google';
        }

        if (in_array('sms', $methods, true) && ! empty($user->phone)) {
            return 'sms';
        }

        if (in_array('email', $methods, true)) {
            return 'email';
        }

        return 'google';
    }

    public function methodLabel(string $method): string
    {
        return match ($method) {
            'sms' => 'SMS',
            'email' => 'Email',
            default => 'Google Authenticator',
        };
    }

    public function sendLoginChallenge(User $user, string $method): void
    {
        if ($method === 'google') {
            return;
        }

        $code = $this->generateCode();
        Cache::put($this->cacheKey($user, $method), Hash::make($code), now()->addMinutes(10));

        if ($method === 'sms') {
            $this->twilioSmsService->sendVerificationCode((string) $user->phone, $code);
            return;
        }

        $user->notify(new TwoFactorOtpNotification($code, $method));
    }

    public function verifyLoginChallenge(User $user, string $method, string $code): bool
    {
        if ($method === 'google') {
            $secret = $user->two_factor_google_secret;

            return ! empty($secret) && (bool) $this->twoFactorProvider->verify($secret, $code);
        }

        $storedCode = Cache::get($this->cacheKey($user, $method));

        return is_string($storedCode) && Hash::check($code, $storedCode);
    }

    public function clearLoginChallenge(User $user, string $method): void
    {
        Cache::forget($this->cacheKey($user, $method));
    }

    private function generateCode(): string
    {
        return str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    }

    private function cacheKey(User $user, string $method): string
    {
        return 'two-factor-login:' . $user->id . ':' . $method;
    }
}