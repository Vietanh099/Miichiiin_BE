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
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'google' => [
        'client_id' => "974580263922-evj11567jcn59daff4f4sql4qkenkuie.apps.googleusercontent.com",
        'client_secret' => "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",
        'redirect' => "http://localhost:8000/login/google/callback",
    ],

    'facebook' => [
        'client_id' => "3496008693949477",
        'client_secret' => "db33b35f9f222952d4008fa224b3916d",
        'redirect' => "http://localhost:8000/login/facebook/callback",
    ],

];
