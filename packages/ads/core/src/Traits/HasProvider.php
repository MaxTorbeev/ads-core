<?php

namespace Ads\Core\Traits;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Route;

trait HasProvider
{
    public function initialization(): void
    {
        //Migration
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        //Factories
        $this->app->make(Factory::class)->load(__DIR__ . '/../database/factories/');

        $this->routes(function () {
            if (file_exists(__DIR__ . '../routes/api.php')) {
                Route::prefix('api')
                    ->middleware('api')
                    ->namespace($this->namespace)
                    ->group(__DIR__ . '../routes/api.php');

            }
        });
    }
}
