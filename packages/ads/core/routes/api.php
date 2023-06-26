<?php

use Ads\Core\Http\Controllers\UserController;
use Ads\Core\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('auth/login', [AuthController::class, 'login'])->name('core.login');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout'])->name('core.logout');

    Route::prefix('users')->group(function() {
        Route::get('/', [UserController::class, 'index'])
            ->name('core.users.index')
            ->can('show_user',  config('auth.providers.users.model'));

        Route::post('/', [UserController::class, 'store'])
            ->name('core.users.store')
            ->can('user_create', config('auth.providers.users.model'));

        Route::patch('/{user}', [UserController::class, 'update'])
            ->name('core.users.update')
            ->can('user_create', config('auth.providers.users.model'));;

        Route::delete('/{user}', [UserController::class, 'destroy'])
            ->name('core.users.destroy')
            ->can('user_delete', config('auth.providers.users.model'));
    });
});
