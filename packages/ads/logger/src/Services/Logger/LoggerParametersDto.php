<?php

namespace Ads\Logger\Services\Logger;

use App\Models\User;

class LoggerParametersDto
{
    private string $uri;

    private User|null $user;

    private array $request;

    private array|string|null $response;

    private string $ip;

    private string $type;

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
     * @return int|null
     */
    public function getExecutingTime(): ?int
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
     * @return array|null
     */
    public function getResponse(): array|string|null
    {
        return $this->response;
    }

    /**
     * @param array|string|null $response
     * @return LoggerParametersDto
     */
    public function setResponse(array|string|null $response): self
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return string
     */
    public function getIp(): string
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
}