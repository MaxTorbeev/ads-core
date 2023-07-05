<?php

namespace Ads\Websockets\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Broadcast::routes(['middleware' => ['auth:sanctum']]);

        require __DIR__ . '/../../routes/channels.php';
    }
}
