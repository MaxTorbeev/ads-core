<?php

use Ads\Logger\Http\Middleware\ApiLoggerMiddleware;
use App\Http\Controllers\WsdlController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/user/1', fn() => response()->success([
    'login' => 'test',
    'password' => 'secret'
], 'get user by id: 1'))->name('user.show')->middleware(ApiLoggerMiddleware::class);


Route::get('/wsdl', [WsdlController::class, 'index'])
    ->name('wsdl')
    ->middleware(ApiLoggerMiddleware::class);
