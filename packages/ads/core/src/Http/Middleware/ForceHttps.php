<?php

namespace Ads\Core\Http\Middleware;

use Illuminate\Http\Request;
use Closure;

class ForceHttps
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->secure() && parse_url(config('app.url'))['scheme'] === 'https') {
            return redirect()->secure($request->getRequestUri(), $request->isMethod('get') ? 301 : 308);
        }

        return $next($request);
    }
}
