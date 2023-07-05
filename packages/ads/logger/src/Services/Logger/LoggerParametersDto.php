<?php

namespace Ads\Logger\Services\Logger;

use App\Models\User;
use stdClass;

class LoggerParametersDto
{
    private string $uri;

    private User|null $user;

    private array $request;

    private array|stdClass|string|null $response;

    private null|string $ip = null;

    private string $type;

    private null|int $response_code = 200;

    private null|string $method = null;

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    public function setUri(string $uri): self
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return LoggerParametersDto
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getExecutingTime(): ?float
    {
        return microtime(true) - LARAVEL_START;
    }

    /**
     * @return array
     */
    public function getRequest(): array
    {
        return $this->request;
    }

    /**
     * @param array $request
     * @return LoggerParametersDto
     */
    public function setRequest(array $request): self
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return array|stdClass|string|null
     */
    public function getResponse(): array|stdClass|string|null
    {
        return $this->response;
    }

    /**
     * @param array|stdClass|string|null $response
     * @return LoggerParametersDto
     */
    public function setResponse(array|stdClass|string|null $response): self
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIp(): ?string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     * @return LoggerParametersDto
     */
    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return LoggerParametersDto
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getResponseCode(): ?int
    {
        return $this->response_code;
    }

    /**
     * @param int|null $response_code
     * @return LoggerParametersDto
     */
    public function setResponseCode(?int $response_code): self
    {
        $this->response_code = $response_code;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMethod(): ?string
    {
        return $this->method;
    }

    /**
     * @param string|null $method
     */
    public function setMethod(?string $method): self
    {
        $this->method = $method;

        return $this;
    }
}
