<?php

namespace App\Http\Controllers;

use Ads\Cache\Services\Cache\CacheService;
use Ads\Core\Services\User\AuthService;
use Ads\Logger\Contracts\Logging\HttpLogger;
use Ads\WsdlClient\Services\Clients\WsdlClient;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WsdlController extends Controller
{
    public function index(WsdlClient $client, AuthService $authService, Request $request): JsonResponse
    {
        auth()->setUser(User::find(1));

        $response = (new CacheService())
            ->setUser($authService->user())
            ->setEntity('tasks.list')
            ->setParams($request->all())
            ->remember(function () use ($client, $authService) {
                return $client
                    ->setUser($authService->user()->userWs)
                    ->setWsdl('itil.1cws')
                    ->request('getListTiket');
            });

        return response()->success($response);
    }
}
