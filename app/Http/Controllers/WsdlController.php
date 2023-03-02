<?php

namespace App\Http\Controllers;

use Ads\Logger\Contracts\Logging\HttpLogger;
use Ads\WsdClient\Services\Clients\WsdlClient;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class WsdlController extends Controller
{
    public function index(HttpLogger $loggerDriver, WsdlClient $client): JsonResponse
    {
        auth()->setUser(User::find(1));

        $response = $client->setUser(auth()->user()->userWs)
            ->setWsdl('itil.1cws')
            ->request('getListTiket');

        return response()->success($response);
    }
}
