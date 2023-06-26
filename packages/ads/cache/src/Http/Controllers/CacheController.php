<?php

namespace Ads\Cache\Http\Controllers;

use Ads\Cache\Http\Requests\CacheRequest;
use Ads\Cache\Services\CacheService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class CacheController extends Controller
{
    public function __construct(
        private CacheService $service
    )
    {
    }

    public function flush(CacheRequest $request): JsonResponse
    {
        return response()->success($this->service->forget(
            $request?->prefix ?? null,
            $request?->index ?? null
        ));
    }
}
