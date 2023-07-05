<?php

namespace Ads\Cache\Http\Controllers;

use Ads\Cache\Http\Requests\CacheRequest;
use Ads\Cache\Services\Cache\CacheService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class CacheController extends Controller
{
    public function flush(CacheRequest $request): JsonResponse
    {
        return response()->success(CacheService::fromRequest($request)->forget());
    }
}
