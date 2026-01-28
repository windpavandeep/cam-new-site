<?php

declare(strict_types=1);

/**
 * One-time script: runs config:cache and route:cache without Terminal/SSH.
 *
 * 1. Upload this file to your app root as run-cache.php (same folder as artisan).
 * 2. In browser open: https://yourdomain.com/pavan/run-cache.php?run=1
 * 3. When you see "Done", DELETE run-cache.php from the server (File Manager).
 *
 * Requires PHP 8.3+ (cPanel: ea-php83). No .env — uses config/site.php.
 */

if (version_compare(PHP_VERSION, '8.3.0', '<')) {
    http_response_code(500);
    exit('PHP 8.3 or higher is required. Current: ' . PHP_VERSION);
}

if (($_GET['run'] ?? '') !== '1') {
    http_response_code(403);
    exit('Add ?run=1 to the URL to run. Delete this file after use.');
}

$base = __DIR__;

if (! is_file($base . '/vendor/autoload.php') || ! is_file($base . '/bootstrap/app.php')) {
    http_response_code(500);
    exit('Put run-cache.php in the app root (next to artisan).');
}

define('LARAVEL_START', microtime(true));

require $base . '/vendor/autoload.php';

$app = require_once $base . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$out = [];
$out[] = 'config:cache: ' . ($kernel->call('config:cache') === 0 ? 'OK' : 'error');
$out[] = 'route:cache: ' . ($kernel->call('route:cache') === 0 ? 'OK' : 'error');

header('Content-Type: text/plain; charset=utf-8');
echo "Cache run:\n" . implode("\n", $out) . "\n\n";
echo "Done. DELETE run-cache.php from the server (File Manager) now.";
