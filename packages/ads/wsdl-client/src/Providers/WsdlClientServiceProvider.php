<?php

namespace Ads\WsdlClient\Providers;

use Illuminate\Support\ServiceProvider;

class WsdlClientServiceProvider extends ServiceProvider
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
        //Migration
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }
}
