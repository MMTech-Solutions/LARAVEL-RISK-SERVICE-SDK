<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Risk API base URL
    |--------------------------------------------------------------------------
    */
    'base_url' => env('MMT_RISK_API_BASE_URL', 'http://127.0.0.1:6051'),

    /*
    |--------------------------------------------------------------------------
    | Bearer token (optional)
    |--------------------------------------------------------------------------
    */
    'api_token' => env('MMT_RISK_API_TOKEN'),

    'default_timeout' => (float) env('MMT_RISK_HTTP_TIMEOUT', 60),

    /*
    |--------------------------------------------------------------------------
    | Extra HTTP headers (merged after defaults and Authorization)
    |--------------------------------------------------------------------------
    |
    | Example: ['X-Api-Key' => env('MMT_RISK_EXTRA_KEY')]
    |
    */
    'headers' => [],
];
