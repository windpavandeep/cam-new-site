<?php

declare(strict_types=1);

/**
 * Use this as index.php when the Laravel app runs in a subfolder (e.g. /pavan)
 * and the contents of public/ have been merged into that folder.
 *
 * Laravel root = same folder as this file (e.g. public_html/pavan/).
 * All settings in config/site.php (no .env). PHP 8.3+ (cPanel: ea-php83).
 */

if (version_compare(PHP_VERSION, '8.3.0', '<')) {
    http_response_code(500);
    exit('PHP 8.3 or higher is required. Current: ' . PHP_VERSION);
}

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$base = __DIR__;

if (file_exists($maintenance = $base . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

require $base . '/vendor/autoload.php';

/** @var Application $app */
$app = require_once $base . '/bootstrap/app.php';

$app->handleRequest(Request::capture());
