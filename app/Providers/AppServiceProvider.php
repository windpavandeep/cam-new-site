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
        // When running in a subfolder with public/ merged (config site.path set),
        // point public_path() to the Laravel root so build/ and models/ resolve.
        if (config('site.path')) {
            $this->app->bind('path.public', fn() => base_path('.'));
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
