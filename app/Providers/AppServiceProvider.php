<?php

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
    }
    /** 
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
