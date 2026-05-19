<?php

use App\Listeners\SendDifferentDeviceLoginNotification;
use App\Models\User;
use App\Notifications\DifferentDeviceLoginNotification;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;

uses(RefreshDatabase::class);

test('sends an email when the user logs in from a different device', function () {
    Notification::fake();

    $user = User::factory()->create([
        'last_login_at' => now()->subDay(),
        'last_login_ip' => '127.0.0.1',
        'last_login_user_agent' => 'Old Browser',
        'last_login_device_hash' => hash('sha256', 'old browser'),
    ]);

    $request = Request::create('/login', 'POST');
    $request->server->set('HTTP_USER_AGENT', 'New Browser');
    $request->server->set('REMOTE_ADDR', '203.0.113.5');
    $this->app->instance('request', $request);

    (new SendDifferentDeviceLoginNotification())->handle(new Login('web', $user, false));

    Notification::assertSentTo($user, DifferentDeviceLoginNotification::class, function (DifferentDeviceLoginNotification $notification) {
        return true;
    });

    $user->refresh();

    expect($user->last_login_ip)->toBe('203.0.113.5')
        ->and($user->last_login_user_agent)->toBe('New Browser')
        ->and($user->last_login_device_hash)->toBe(hash('sha256', 'new browser'));
});
