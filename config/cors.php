<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | The join-waitlist form lives on the marketing site (unitycarelink.com)
    | and posts to this app's /api/register-join-waitlist on a different
    | subdomain (portal.unitycarelink.com). supports_credentials must be
    | true and allowed_origins must be explicit (not "*") so the browser
    | will store the session cookie from that cross-origin response.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => array_filter(array_map('trim', explode(',', env(
        'CORS_ALLOWED_ORIGINS',
        'https://unitycarelink.com,https://www.unitycarelink.com'
    )))),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
