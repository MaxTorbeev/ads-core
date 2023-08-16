<?php

namespace Ads\Core\Services\Client;

class HttpClientService extends AbstractHttpClientService
{
    /**
     * @param string $url http domain or path to remote server.
     */
    public function __construct(string $url)
    {
        parent::__construct();

        $this->serverUrl = $url;
    }

    public function request(string $method, string $url, array $params = []): mixed
    {
        return $this->call($method, $url, $params)->json();
    }
}
