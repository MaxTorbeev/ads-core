<?php

namespace Ads\Logger\Middleware;

use Ads\Logger\Contracts\Logging\LoggerDriver;
use Ads\Logger\Enums\LogTypes;
use Ads\Logger\Services\Logger\LoggerParametersDto;
use Closure;
use Illuminate\Http\Request;

class ApiLoggerMiddleware
{
    protected LoggerDriver $logger;

    public function __construct(LoggerDriver $logger)
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
        $logger = $this->logger->request(
            (new LoggerParametersDto())
                ->setType(LogTypes::HTTP->value)
                ->setIp($request->ip())
                ->setRequest($request->all())
                ->setUri($request->path())
                ->setUser($request->getUser())
        );

        $response = $next($request);

        $logger->response(
            (new LoggerParametersDto())->setResponse($response->original)
        );

        return $response;
    }
}
