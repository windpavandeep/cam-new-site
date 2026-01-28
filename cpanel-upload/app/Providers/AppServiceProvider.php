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
        // When running in a subfolder (config site.path set), point public_path() to the app root.
        if (config('site.path')) {
            $this->app->bind('path.public', fn () => base_path('.'));
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
