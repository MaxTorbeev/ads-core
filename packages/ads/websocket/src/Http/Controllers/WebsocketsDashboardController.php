<?php

namespace Ads\Websockets\Http\Controllers;

use App\Http\Controllers\Controller;
use BeyondCode\LaravelWebSockets\Apps\AppProvider;
use BeyondCode\LaravelWebSockets\Dashboard\DashboardLogger;
use Illuminate\Http\Request;

class WebsocketsDashboardController extends Controller
{
    public function __invoke(Request $request, AppProvider $apps)
    {
        return response()->success('', [
            'apps' => $apps->all(),
            'csrfToken' => csrf_token(),
            'logChannelPrefix' => DashboardLogger::LOG_CHANNEL_PREFIX,
        ]);
    }
}
