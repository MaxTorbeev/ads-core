<?php

use Ads\Core\Http\Controllers\UserController;
use Ads\Core\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Ads\Core\Http\Controllers\PasswordController;

Route::post('auth/login', [AuthController::class, 'login'])->name('login');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout'])->name('logout');

    Route::post('password/restore', [PasswordController::class, 'restore']);
    Route::post('password/change', [PasswordController::class, 'change']);

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

        Route::get('/{user}', [UserController::class, 'show'])
            ->name('users.show')
            ->can('user_show', 'user');
    });
});
