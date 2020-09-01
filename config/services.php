<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\Entities\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

    'token' => [
        'type' => env('TOKEN_TYPE', 'bearer'),
        'expireTime' => env('TOKEN_EXPIRED_TIME', 60)
    ],

    'push' => [
        'host' => env('PUSHER_HOST', 'https://go.urbanairship.com'),
        'token' => env('PUSHER_TOKEN_TYPE', 'Bearer').' '.env('PUSHER_TOKEN'),
        'headers' => [
            'accept' => env('PUSHER_HEADER_ACCEPT', 'application/vnd.urbanairship+json; version=3;'),
            'content_type' => env('PUSHER_HEADER_CONTENT_TYPE', 'application/json')
        ],
        'provider' => env('PUSHER_PROVIDER', 'airship'),
        'push_uri' => env('PUSHSER_PUSH_URI', '/api/push'),
        'associate_uri' => env('PUSHER_ASSOCIATE_URI', '/api/named_users/associate'),
        'disassociate_uri' => env('PUSHER_DISASSOCIATE_URI', 'api/named_users/disassociate')
    ]

];
