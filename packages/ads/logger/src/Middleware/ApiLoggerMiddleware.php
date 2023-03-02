<?php

namespace Ads\Logger\Middleware;

use Ads\Logger\Contracts\Logging\HttpLogger;
use Ads\Logger\Enums\LogTypes;
use Ads\Logger\Services\Logger\LoggerParametersDto;
use Closure;
use Illuminate\Http\Request;

class ApiLoggerMiddleware
{
    protected HttpLogger $logger;

    public function __construct(HttpLogger $logger)
    {
        $this->logger = $logger;
    }


    /**
     * Handle log middleware.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $logParams = (new LoggerParametersDto())
            ->setType(LogTypes::HTTP->value)
            ->setIp($request->ip())
            ->setRequest($request->all())
            ->setUri($request->path())
            ->setUser($request->getUser());

        $logger = $this->logger->request($logParams);

        $response = $next($request);

        $logger->response(
            $logParams
                ->setResponse($response->original['data'] ?? $response->original)
                ->setUser(auth()->user())
        );

        return $response;
    }
}
