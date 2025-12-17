<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Lorisleiva\Actions\Facades\Actions;
use Override;

/**
 * Class AppServiceProvider
 *
 * The main application service provider that bootstraps and registers services.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        Actions::registerRoutes();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
