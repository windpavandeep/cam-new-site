<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Contracts\Foundation\ExceptionRenderer;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;

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

        // Use a simple HTML exception renderer so we don't depend on the framework's
        // renderer dist/scripts.js (which may be missing on cPanel / production deploys).
        $this->app->singleton(ExceptionRenderer::class, function () {
            return new class implements ExceptionRenderer
            {
                /**
                 * @param  \Throwable  $throwable
                 * @return string
                 */
                public function render($throwable)
                {
                    $renderer = new HtmlErrorRenderer(config('app.debug'));

                    return $renderer->render($throwable)->getAsString();
                }
            };
        });
    }
    /** 
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
