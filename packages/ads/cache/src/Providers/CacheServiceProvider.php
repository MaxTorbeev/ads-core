<?php

namespace Ads\Cache\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        if (file_exists(__DIR__ . '/../../routes/api.php')) {
            Route::prefix('api/cache')
                ->middleware('api')
                ->group(__DIR__ . './../../routes/api.php');
        }

        $this->publishes([
            __DIR__ . '/../../config/ads-logger.php' => config_path('ads-logger.php')
        ], 'config');
    }
}
