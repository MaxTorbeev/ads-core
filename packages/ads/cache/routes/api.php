<?php
use Illuminate\Support\Facades\Route;
use Ads\Cache\Http\Controllers\CacheController;

Route::post('flush', [CacheController::class, 'flush'])->name('cache.flush');
