<?php

namespace Ads\Logger\Providers;

use Ads\Logger\Contracts\Logging\HttpLogger;
use Illuminate\Support\ServiceProvider;

class LoggerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->initialization();
    }

    public function initialization(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/package.php' => config_path('ads-logger.php')
        ], 'config');

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $this->app->singleton(HttpLogger::class, config('ads-logger.driver'));
    }
}
