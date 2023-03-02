<?php

namespace Ads\Logger\Services\Logger;

use Ads\Logger\Contracts\Logging\HttpLogger;
use Ads\Logger\Models\Log;
use Illuminate\Support\Facades\Route;

class DefaultHttpLogger extends AbstractDefaultLogger implements HttpLogger
{

}
