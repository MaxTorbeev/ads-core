<?php

namespace App\Http\Controllers;

use Ads\Logger\Contracts\Logging\LoggerDriver;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class WsdlController extends Controller
{
    public function index(LoggerDriver $loggerDriver): JsonResponse
    {
        auth()->setUser(User::find(1));

        return response()->success($loggerDriver->getModel()->toArray());
    }
}
