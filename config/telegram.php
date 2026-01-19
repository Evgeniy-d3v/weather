<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Telegram Bot Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Telegram Bot API integration
    |
    */

    'bot_token' => env('TELEGRAM_BOT_TOKEN'),

    'bot_username' => env('TELEGRAM_BOT_USERNAME'),

    'webhook_url' => env('APP_URL') . env('TELEGRAM_WEBHOOK_URL'),

    /*
    |--------------------------------------------------------------------------
    | Update Polling Settings
    |--------------------------------------------------------------------------
    |
    | Settings for getUpdates method polling
    |
    */

    'polling' => [
        'timeout' => env('TELEGRAM_POLLING_TIMEOUT', 60),
        'limit' => env('TELEGRAM_POLLING_LIMIT', 100),
    ],
];

