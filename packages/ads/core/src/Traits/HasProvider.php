<?php

namespace Ads\Core\Traits;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Route;


trait HasProvider
{
    public function initialization(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/package.php' => config_path('core.php')
        ], 'config');

        //Migration
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        //Factories
        // todo Надо посмотреть что там в laravel 8
//        $this->app->make(Factory::class)->load(__DIR__ . '/../database/factories/');

        if (file_exists(__DIR__ . '/../../routes/api.php')) {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(__DIR__ . './../../routes/api.php');
        }

    }
}
