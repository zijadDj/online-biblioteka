<?php

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    'guards' => [
    // ... postojeći guard

    'librarian' => [
        'driver' => 'session',
        'provider' => 'librarians',
    ],
],
    'librarians' => [
        'driver' => 'eloquent',
        'model' => App\Models\Librarian::class,
    ],




    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        //  Dodato za librarians
        'librarians' => [
            'driver' => 'eloquent',
            'model' => App\Models\Librarian::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        // Dodato za librarians
        'librarians' => [
            'provider' => 'librarians',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
