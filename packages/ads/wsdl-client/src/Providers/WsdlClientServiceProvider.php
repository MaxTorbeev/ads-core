<?php

namespace Ads\WsdClient\Providers;

use Ads\Core\Traits\HasProvider;
use Illuminate\Support\ServiceProvider;

class WsdlClientServiceProvider extends ServiceProvider
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
    }

    public function initialization(): void
    {
        //Migration
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }
}
