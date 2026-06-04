<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Filament Version
    |--------------------------------------------------------------------------
    |
    | This value is used to help identify the version of Filament being used.
    |
    */

    'version' => '3.2',

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | This is the storage disk Filament will use to store files. You may use
    | any of the disks defined in the `config/filesystems.php` file and you
    | may add custom disks to that file as needed.
    |
    */

    'default_filesystem_disk' => env('FILAMENT_FILESYSTEM_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Default Avatar Provider
    |--------------------------------------------------------------------------
    |
    | This configuration value determines which provider will be used to
    | retrieve the user avatar when a user is using Filament.
    |
    */

    'default_avatar_provider' => \Filament\AvatarProviders\UiAvatarsProvider::class,

    /*
    |--------------------------------------------------------------------------
    | Default Panel
    |--------------------------------------------------------------------------
    |
    | Below you may change the default panel that should be active for users.
    |
    */

    'default_panel' => 'admin',

    /*
    |--------------------------------------------------------------------------
    | Panels
    |--------------------------------------------------------------------------
    |
    | Below you may configure the panels that your application exposes. A
    | panel is a discrete set of dashboard resources and pages, integrated
    | into a single user interface.
    |
    | Learn more: https://filamentphp.com/docs/admin/panels
    |
    */

    'panels' => [
        'admin' => [
            'id' => 'admin',
            'path' => 'admin-panel',
            'auth_middleware' => [
                'web',
                \Illuminate\Session\Middleware\AuthenticateSession::class,
            ],
            'user_model' => \App\Models\User::class,
            'colors' => [
                'primary' => '#2563eb',
                'danger' => '#ef4444',
            ],
            'favicon' => '/storage/app/public/logo/logo.png',
            'registration_route' => false,
            'login_route_name' => 'filament.admin.auth.login',
        ],
    ],

];
