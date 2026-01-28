<?php

/** @var array<string, mixed> $site From config/site.php (cPanel, no .env). */
$site = is_file(__DIR__ . '/site.php') ? (require __DIR__ . '/site.php') : [];

return [

    'name' => $site['name'] ?? 'Laravel',

    'env' => $site['env'] ?? 'production',

    'debug' => (bool) ($site['debug'] ?? false),

    'url' => $site['url'] ?? 'http://localhost',

    'asset_url' => $site['asset_url'] ?? null,

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. The timezone
    | is set to "UTC" by default as it is suitable for most use cases.
    |
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by Laravel's translation / localization methods. This option can be
    | set to any locale for which you plan to have translation strings.
    |
    */

    'locale' => $site['locale'] ?? 'en',

    'fallback_locale' => $site['fallback_locale'] ?? 'en',

    'faker_locale' => $site['faker_locale'] ?? 'en_US',

    'cipher' => 'AES-256-CBC',

    'key' => $site['key'] ?? null,

    'previous_keys' => $site['previous_keys'] ?? [],

    'maintenance' => [
        'driver' => $site['maintenance_driver'] ?? 'file',
        'store' => $site['maintenance_store'] ?? 'database',
    ],

];
