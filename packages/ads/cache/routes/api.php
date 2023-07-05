<?php

use Ads\Cache\Http\Controllers\CacheController;
use Ads\Logger\Http\Middleware\ApiLoggerMiddleware;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum', ApiLoggerMiddleware::class])->group(function () {
    Route::post('flush', [CacheController::class, 'flush'])
        ->name('cache.flush')
        ->can('cache_clear', config('auth.providers.users.model'));
});
