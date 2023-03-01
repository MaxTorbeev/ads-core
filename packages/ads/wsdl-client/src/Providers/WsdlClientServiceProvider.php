<?php

namespace Ads\WsdClient\Providers;

use Ads\Core\Contracts\Provider\AdsServiceProvider;
use Ads\Core\Traits\HasProvider;
use Illuminate\Support\ServiceProvider;

class WsdlClientServiceProvider extends ServiceProvider implements AdsServiceProvider
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

    public function observers(): void
    {
        // TODO: Implement observers() method.
    }

    public function initialization(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/wsdl-client.php' => config_path('wsdl-client.php')
        ], 'config');

        //Migration
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }
}
