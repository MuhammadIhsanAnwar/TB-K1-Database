<?php

use Laravel\Jetstream\Features;

return [

    /*
    |--------------------------------------------------------------------------
    | Jetstream Stack
    |--------------------------------------------------------------------------
    |
    | This configuration value informs Jetstream which "stack" you will be
    | using for your application. In addition, you may set any options
    | for the stack. This can include library versions and more.
    |
    */

    'stack' => 'livewire',

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    |
    | Some of Jetstream's features are optional. You may disable the features
    | that are not needed for your particular application.
    |
    */

    'features' => [
        // Features::profilePhotos(),
        Features::api(),
        Features::teams(['invitations' => true]),
    ],

    /*
    |--------------------------------------------------------------------------
    | Profile Photo Disk
    |--------------------------------------------------------------------------
    |
    | This configuration value determines which disk Jetstream's profile photo
    | functionality will use when storing profile photos for your users.
    |
    */

    'profile_photo_disk' => 'public',

];
