<?php

namespace Ads\Logger\Providers;

use Ads\Core\Contracts\Provider\AdsServiceProvider;
use Ads\Core\Observers\LogObserver;
use Ads\Core\Traits\HasProvider;
use Ads\Logger\Contracts\Logging\LoggerDriver;
use Ads\Logger\Models\Log;
use Illuminate\Support\ServiceProvider;

class LoggerServiceProvider extends ServiceProvider implements AdsServiceProvider
{
    use HasProvider;

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->initialization();
        $this->observers();
    }

    public function initialization(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/package.php' => config_path('ads-logger.php')
        ], 'config');

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $this->app->singleton(LoggerDriver::class, config('ads-logger.driver'));
    }

    /**
     * Defining Observers
     */
    public function observers(): void
    {
        Log::observe(LogObserver::class);
    }
}
