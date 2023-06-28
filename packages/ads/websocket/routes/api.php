<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Arr;
use BeyondCode\LaravelWebSockets\Dashboard\Http\Middleware\Authorize as AuthorizeDashboard;
use Ads\Websockets\Http\Controllers\WebsocketsDashboardController;

Route::prefix(config('websockets.path'))->group(static function () {
    Route::middleware(Arr::collapse([config('websockets.middleware'), [AuthorizeDashboard::class]]))->group(static function () {
        Route::get('/', WebsocketsDashboardController::class);
    });
});

