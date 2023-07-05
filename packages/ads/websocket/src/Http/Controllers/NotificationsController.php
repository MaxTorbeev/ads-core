<?php

namespace Ads\Websockets\Http\Controllers;

use Ads\Websockets\Events\UpdatePageNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class NotificationsController extends Controller
{
    public function updatePage(): JsonResponse
    {
        event(UpdatePageNotification::class);

        return response()->success(true);
    }
}
