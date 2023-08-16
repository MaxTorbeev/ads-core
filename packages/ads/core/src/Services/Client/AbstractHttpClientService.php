<?php

namespace Ads\Core\Services\Client;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

abstract class AbstractHttpClientService
{
    protected string $serverUrl;

    private PendingRequest $client;

    public function __construct()
    {
        $this->client = Http::asJson();
    }

    /**
     * Использовать http клиент с форматом formData.
     *
     * @return $this
     */
    public function asForm(): self
    {
        $this->client = Http::asForm();

        return $this;
    }

    /**
     * Set access token.
     *
     * @param string $token
     * @return $this
     */
    public function setToken(string $token): self
    {
        $this->client = $this->client->withToken($token);

        return $this;
    }

    /**
     * Execute http client and call remote data.
     *
     * @param string $method - HTTP method. GET, POST, PATCH, PUT, DELETE
     * @param string $url - remote url
     * @param array $params - params for remote request
     * @return mixed
     */
    public function call(string $method, string $url, array $params = []): mixed
    {
        $response = $this->client->{$method}($this->serverUrl . $url, $params);

        Log::info("Request to $this->serverUrl", [
            'method' => $this->serverUrl,
            'url' => $url,
            'params' => $params,
            'response' => $response
        ]);

        return $response;
    }

    /**
     * Implement this method for remote request.
     *
     * @param string $method - HTTP method. GET, POST, PATCH, PUT, DELETE
     * @param string $url - remote url
     * @param array $params - params for remote request
     * @return mixed
     */
    abstract public function request(string $method, string $url, array $params = []): mixed;
}
