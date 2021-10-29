<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
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

    'twilio' => [
        'account_sid' => env('TWILIO_ACCOUNT_SID'),
        'auth_token' => env('TWILIO_AUTH_TOKEN'),
        'use_sandbox' => env('TWILIO_SANDBOX')
    ],

    'gotify' => [
        'app_port' => env('GOTIFY_APP_PORT'),
        'app_port_ssl' => env('GOTIFY_APP_PORT_SSL'),
        'user' => env('GOTIFY_USERNAME'),
        'pass' => env('GOTIFY_PASSWORD'),
        'url' => env('GOTIFY_URL'),
        'public_url' => env('GOTIFY_PUBLIC_URL')
    ],

];
