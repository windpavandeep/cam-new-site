<?php

declare(strict_types=1);

/**
 * Site configuration for cPanel deployment without .env
 *
 * Edit this file on the server. Requires PHP 8.3+ (cPanel: Select PHP Version → ea-php83).
 */

return [

    'name' => 'CAM Solutions',

    'env' => 'local',

    'debug' => true,

    /*
    |--------------------------------------------------------------------------
    | Application URL (include subfolder if used, e.g. /pavan)
    |--------------------------------------------------------------------------
    */
    'url' => 'http://127.0.0.1:8000',
    // 'url' => 'https://camsolution.thewindai.com',

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
    // 'asset_url' => 'https://camsolution.thewindai.com',

    /*
    |--------------------------------------------------------------------------
    | Asset URLs when document root is the Laravel project folder (cPanel)
    |--------------------------------------------------------------------------
    | Set true if the web server serves the site from the folder that contains
    | app/, vendor/, and public/ (not from public/ itself). Then browser URLs use
    | /public/slider/... while the database still stores paths without "public/"
    | (e.g. slider/file.jpg → file on disk at public/slider/file.jpg).
    | Use false when the document root is the public/ folder (e.g. php artisan serve).
    */
    'assets_use_public_prefix' => false,

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


    // Production use only

    // 'db_connection' => 'mysql',
    // 'db_host' => '127.0.0.1',
    // 'db_port' => '3306',
    // 'db_database' => 'foodfitd_cam',
    // 'db_username' => 'foodfitd_camuser',
    // 'db_password' => 'jRyyF=LR2B^3z}()',



];
