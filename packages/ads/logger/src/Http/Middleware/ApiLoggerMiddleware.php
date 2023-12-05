<?php

namespace Ads\Logger\Http\Middleware;

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
            ->setType(LogTypes::API->value)
            ->setIp($request->ip())
            ->setRequest($request->all())
            ->setUri($request->path())
            ->setMethod($request->getMethod())
            ->setUser($request->getUser());

        $logger = $this->logger->request($logParams);

        $response = $next($request);

        $logParams->setResponseCode(
            $response->exception->status
            ?? (method_exists($response, 'status') ? $response->status() : null)
            ?? (method_exists($response, 'getStatusCode') ? $response->getStatusCode() : null),
        );

        $logger->response(
            $logParams
                ->setResponse(
                    $response->original['data'] ?? $response->original,
                    $response->original['message'] ?? null
                )
                ->setUser(auth()->user())
        );

        return $response;
    }
}
