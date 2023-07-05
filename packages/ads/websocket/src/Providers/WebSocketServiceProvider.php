<?php
namespace Ads\Websockets\Providers;

use BeyondCode\LaravelWebSockets\WebSocketsServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class WebsocketServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (file_exists(__DIR__ . '/../../routes/api.php')) {
            Route::prefix('api')
                ->middleware(['api', 'auth:sanctum'])
                ->group(__DIR__ . './../../routes/api.php');
        }

        $this->publishes([
            __DIR__ . '/../../config/websockets.php' => config_path('websockets.php')
        ], 'config');
    }
    public function register(): void
    {
        $this->app->register(WebSocketsServiceProvider::class);
        $this->app->register(BroadcastServiceProvider::class);
    }
}
