<?php

use Ads\Core\Http\Controllers\UserController;
use Ads\Core\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('auth/login', [AuthController::class, 'login'])->name('login');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('users')->group(function() {
        Route::get('/', [UserController::class, 'index'])
            ->name('users.index')
            ->can('user_show',  config('auth.providers.users.model'));

        Route::post('/', [UserController::class, 'store'])
            ->name('users.store')
            ->can('user_create', config('auth.providers.users.model'));

        Route::patch('/{user}', [UserController::class, 'update'])
            ->name('users.update')
            ->can('user_create', 'user');

        Route::delete('/{user}', [UserController::class, 'destroy'])
            ->name('users.destroy')
            ->can('user_delete', 'user');

        Route::get('/info', [UserController::class, 'info'])->name('users.info');
    });
});
