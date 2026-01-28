<?php

/**
 * Site configuration for cPanel deployment without .env
 *
 * Edit this file on the server. Requires PHP 8.3+ (ea-php83).
 */

return [

    'name' => 'CAM Solution',

    'env' => 'local',

    'debug' => true,

    /*
    |--------------------------------------------------------------------------
    | Application URL (include subfolder if used, e.g. /pavan)
    |--------------------------------------------------------------------------
    */
    'url' => 'http://127.0.0.1:8000',

    /*
    |--------------------------------------------------------------------------
    | Subfolder path (e.g. pavan). Empty string when app is at domain root.
    |--------------------------------------------------------------------------
    */
    'path' => '',

    /*
    |--------------------------------------------------------------------------
    | Asset URL (same as url when in subfolder, so /pavan/build/... works)
    |--------------------------------------------------------------------------
    */
    'asset_url' => 'http://127.0.0.1:8000',

    /*
    |--------------------------------------------------------------------------
    | Application Key (REQUIRED)
    |--------------------------------------------------------------------------
    | Generate: php artisan key:generate --show
    | Paste the "base64:..." value here. Do not leave empty in production.
    */
    'key' => 'base64:+w04vS/9Jm6gSaaMAzp0UeMCPUhhXmzGmXqkPO+ezVM=',

    'locale' => 'en',

    'fallback_locale' => 'en',

    'faker_locale' => 'en_US',

    'previous_keys' => [],

    'maintenance_driver' => 'file',

    'maintenance_store' => 'database',

    /*
    |--------------------------------------------------------------------------
    | Database (no .env — set here)
    |--------------------------------------------------------------------------
    */
    'db_connection' => 'mysql',
    'db_host' => '127.0.0.1',
    'db_port' => '3305',
    'db_database' => 'cam_host',
    'db_username' => 'cam',
    'db_password' => 'Admin@123#',
];
