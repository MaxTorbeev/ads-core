<?php

namespace Ads\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Log
{
    /**
     * Handle log middleware.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
