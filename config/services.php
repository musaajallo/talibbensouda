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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
     * Privacy-friendly, cookieless analytics (Plausible-compatible: Plausible,
     * a self-hosted instance, or a drop-in like Umami with a script URL).
     * Nothing loads and the CSP stays closed until PLAUSIBLE_DOMAIN is set.
     * See resources/views/partials/analytics.blade.php + App\Support\Csp\AppPreset.
     */
    'analytics' => [
        'domain' => env('PLAUSIBLE_DOMAIN'),
        'src' => env('PLAUSIBLE_SRC', 'https://plausible.io/js/script.js'),
    ],

];
