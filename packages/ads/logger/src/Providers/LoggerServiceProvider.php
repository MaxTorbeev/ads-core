<?php

namespace Ads\Logger\Providers;

use Ads\Logger\Contracts\Logging\HttpLogger;
use Ads\Logger\Enums\LogTypes;
use Ads\Logger\ScheduledActions\LogsScheduleAction;
use Ads\Logger\Services\Logger\LoggerParametersDto;
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
        $this->appTerminatedLog();
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

    private function appTerminatedLog(): void
    {
        $this->app->terminating(function(){
            $logParams = (new LoggerParametersDto())
                ->setType(LogTypes::SYSTEM->value)
                ->setIp(request()->ip())
                ->setRequest(request()->all())
                ->setUri(request()->path())
                ->setMethod(request()->getMethod())
                ->setUser(request()->getUser());

            $logger = $this->app->make(HttpLogger::class);

            $logger
                ->request($logParams)
                ->response($logParams->setResponse('Запрос завершился в связи с истечением времени'));
        });
    }
}
