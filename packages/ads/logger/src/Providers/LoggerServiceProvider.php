<?php

namespace Ads\Logger\Providers;

use Ads\Logger\Contracts\Logging\HttpLogger;
use Ads\Logger\ScheduledActions\LogsScheduleAction;
use Illuminate\Console\Scheduling\Schedule;
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

    public function register()
    {

    }

    public function initialization(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/ads-logger.php' => config_path('ads-logger.php')
        ], 'config');

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $this->app->singleton(HttpLogger::class, config('ads-logger.driver'));

        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->call(new LogsScheduleAction())->dailyAt('00:00');
        });
    }
}
