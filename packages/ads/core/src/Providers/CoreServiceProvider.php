<?php

namespace Ads\Core\Providers;

use Ads\Core\Contracts\Provider\AdsServiceProvider;
use Ads\Core\Models\Log;
use Ads\Core\Observers\LogObserver;
use Ads\Core\Traits\HasProvider;
use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider implements AdsServiceProvider
{
    use HasProvider;

    protected $namespace;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->initialization();
        $this->observers();
    }

    /**
     * Defining Observers
     */
    public function observers(): void
    {
        Log::observe(LogObserver::class);
    }
}
