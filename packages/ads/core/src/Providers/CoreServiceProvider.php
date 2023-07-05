<?php

namespace Ads\Core\Providers;

use Ads\Core\Traits\HasProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    use HasProvider;

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->initialization();
    }

    public function initialization(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/core.php' => config_path('core.php')
        ], 'config');

        //Migration
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        if (file_exists(__DIR__ . '/../../routes/api.php')) {
            Route::prefix('api')
                ->middleware('api')
                ->group(__DIR__ . './../../routes/api.php');
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(ResponseMacroServiceProvider::class);
    }
}
