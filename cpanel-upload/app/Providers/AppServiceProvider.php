<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // When build/ is in the app root (cPanel: public merged into root), point
        // public_path() to the Laravel root so Vite finds manifest.json and assets.
        $build_in_root = is_file(base_path('build/manifest.json')) || is_dir(base_path('build'));
        if ($build_in_root) {
            $this->app->bind('path.public', fn () => base_path('.'));
        }

        // Ensure Vite uses the correct asset URL for subfolder deployments
        if ($assetUrl = config('app.asset_url')) {
            $_ENV['VITE_ASSET_URL'] = $assetUrl;
        }
    }
    /** 
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
