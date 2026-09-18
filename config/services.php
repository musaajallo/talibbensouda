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
     * YouTube Data API v3 — powers the admin's "Import YouTube Videos" page
     * (App\Filament\Admin\Pages\ImportYoutubeVideos / App\Support\YouTube\YouTubeChannelClient).
     * Free API key from https://console.cloud.google.com — enable "YouTube
     * Data API v3" on a project, then Credentials -> Create API key. No
     * billing required at this volume. The import page shows a clear notice
     * instead of erroring when the key is blank.
     */
    'youtube' => [
        'api_key' => env('YOUTUBE_API_KEY'),
        'channel_handle' => env('YOUTUBE_CHANNEL_HANDLE', '@talibforpresident'),
    ],

];
