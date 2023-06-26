<?php

namespace App\Http\Controllers;

use Ads\Cache\Services\CacheService;
use Ads\Logger\Contracts\Logging\HttpLogger;
use Ads\WsdClient\Services\Clients\WsdlClient;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WsdlController extends Controller
{
    public function index(HttpLogger $loggerDriver, WsdlClient $client, Request $request): JsonResponse
    {
        auth()->setUser(User::find(1));

        $response = (new CacheService())
            ->setParams($request->all())
            ->remember(function () use ($client) {
                $client->setUser(auth()->user()->userWs)
                    ->setWsdl('itil.1cws')
                    ->request('getListTiket');
            });


        return response()->success($response);
    }
}
