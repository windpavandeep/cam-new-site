<?php

declare(strict_types=1);

/**
 * Use this as public_html/index.php when the document root must be public_html
 * and the Laravel app lives in a sibling folder (e.g. ~/cam-solution).
 *
 * 1. Copy this file to public_html/index.php
 * 2. Set LARAVEL_APP_DIR to your app folder (same level as public_html).
 *    Example: /home/username/cam-solution
 *
 * Requires PHP 8.3+ (cPanel: ea-php83).
 */

if (version_compare(PHP_VERSION, '8.3.0', '<')) {
    http_response_code(500);
    exit('PHP 8.3 or higher is required. Current: ' . PHP_VERSION);
}

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Folder containing app/, bootstrap/, config/, vendor/, etc. (sibling to public_html)
define('LARAVEL_APP_DIR', __DIR__ . '/../cam-solution');

$maintenance = LARAVEL_APP_DIR . '/storage/framework/maintenance.php';
if (file_exists($maintenance)) {
    require $maintenance;
}

require LARAVEL_APP_DIR . '/vendor/autoload.php';

/** @var Application $app */
$app = require_once LARAVEL_APP_DIR . '/bootstrap/app.php';

$app->handleRequest(Request::capture());
