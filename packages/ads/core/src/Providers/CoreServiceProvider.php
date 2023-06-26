<?php

namespace Ads\Core\Providers;

use Ads\Cache\Providers\CacheServiceProvider;
use Ads\Core\Contracts\Provider\AdsServiceProvider;
use Ads\Core\Observers\LogObserver;
use Ads\Core\Traits\HasProvider;
use Ads\Logger\Contracts\Logging\HttpLogger;
use Ads\Logger\Models\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Ads\Core\Policies\RolePolicies;

class CoreServiceProvider extends ServiceProvider implements AdsServiceProvider
{
    use HasProvider;

    protected $namespace;

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->initialization();
        $this->observers();
        $this->initRoles();
    }

    public function initialization(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/core.php' => config_path('core.php')
        ], 'config');

        //Migration
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        if (file_exists(__DIR__ . '/../../routes/api.php')) {
            Route::prefix('api/core')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(__DIR__ . './../../routes/api.php');
        }

        $this->app->singleton(HttpLogger::class, config('core.logger'));
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(CacheServiceProvider::class);
        $this->app->register(ResponseMacroServiceProvider::class);
    }

    /**
     * Defining Observers
     */
    public function observers(): void
    {
        Log::observe(LogObserver::class);
    }

    public function initRoles(): void
    {
        RolePolicies::define();
    }
}
