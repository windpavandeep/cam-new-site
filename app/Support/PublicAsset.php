<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Http\Request;

/**
 * Build URLs for files stored under Laravel's public/ directory.
 *
 * When the web server's document root is the project root (folder containing app/, vendor/, public/),
 * requests must use the /public/ path segment so Apache can find files on disk.
 */
final class PublicAsset
{
    /**
     * Absolute path inside Laravel's physical public/ directory (for move, unlink, is_file).
     *
     * Always use this (not public_path()) for slider/media so cPanel subfolder installs still
     * write to project/public/slider even when path.public is rebound to the project root.
     */
    public static function diskPath(string $relative): string
    {
        $relative = ltrim(str_replace('\\', '/', $relative), '/');
        $relative = (string) preg_replace('#\.\.+#', '', $relative);

        return base_path('public/' . $relative);
    }

    public static function url(string $path): string
    {
        $path = ltrim($path, '/');
        if ($path === '') {
            return asset('');
        }

        return self::usePublicPrefix()
            ? asset('public/' . $path)
            : asset($path);
    }

    public static function usePublicPrefix(): bool
    {
        return (bool) config('site.assets_use_public_prefix', false);
    }

    /**
     * Ensure a directory under public/ exists (for uploads). Does not assert is_writable (some hosts mis-report).
     *
     * @param  string  $relative  e.g. "slider" or "media/thumbnails"
     * @return string|null  Error message for the user, or null if OK
     */
    public static function ensureWritablePublicDirectory(string $relative): ?string
    {
        $dir = self::diskPath(trim($relative, '/'));
        if (! is_dir($dir)) {
            if (! @mkdir($dir, 0775, true) && ! is_dir($dir)) {
                return 'Could not create '.$dir.'. Ensure the project folder is writable by the PHP user (cPanel: folder perms 0755, owner your account). Create public/ and chmod 775 if needed.';
            }
        }
        @chmod($dir, 0775);

        return null;
    }

    /**
     * True when the browser sent a body (usually a file) but PHP did not populate uploaded files
     * (typical when post_max_size or upload_max_filesize is too small on cPanel).
     */
    public static function likelyPhpUploadLimitRejected(Request $request, string $file_input): bool
    {
        if (! $request->isMethod('POST')) {
            return false;
        }
        if ($request->hasFile($file_input)) {
            return false;
        }
        $length = (int) $request->header('Content-Length', 0);

        return $length > 1024;
    }

    /** Human-readable hint for cPanel / MultiPHP INI limits. */
    public static function phpUploadLimitsHint(): string
    {
        return 'Current PHP limits: upload_max_filesize='.ini_get('upload_max_filesize')
            .', post_max_size='.ini_get('post_max_size')
            .'. In cPanel use MultiPHP INI Editor (or .user.ini in your site root) and set post_max_size larger than upload_max_filesize; then reload.';
    }
}
