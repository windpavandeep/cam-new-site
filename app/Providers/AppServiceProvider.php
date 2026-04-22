<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\ContactMessage;
use App\Models\JobApplication;
use Illuminate\Contracts\Foundation\ExceptionRenderer;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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
        View::composer('layouts.dashboard', function (\Illuminate\View\View $view): void {
            $unread_contact_messages_count = 0;
            $unread_job_applications_count = 0;
            $user = auth()->user();
            if ($user !== null && $user->isAdmin()) {
                if (Schema::hasTable('contact_messages')) {
                    $unread_contact_messages_count = ContactMessage::query()->whereNull('read_at')->count();
                }
                if (Schema::hasTable('job_applications')) {
                    $unread_job_applications_count = JobApplication::query()->whereNull('read_at')->count();
                }
            }
            $view->with('unread_contact_messages_count', $unread_contact_messages_count);
            $view->with('unread_job_applications_count', $unread_job_applications_count);
        });
    }
}
