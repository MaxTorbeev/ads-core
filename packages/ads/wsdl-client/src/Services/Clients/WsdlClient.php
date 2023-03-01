<?php

namespace Ads\WsdClient\Services\Clients;

use Ads\WsdClient\Exceptions\SoapException;
use Ads\WsdClient\Models\UserWs;
use Illuminate\Support\Facades\Auth;

class WsdlClient
{
    private UserWs $user;

    private string $wsdl;

    public function __construct(?UserWs $user)
    {
        $this->user = $user
            ? clone($user)
            : clone(Auth::user()->userWs);
    }

    public function setUser(UserWs $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function setWsdl(string $wsdl): self
    {
        $this->wsdl = $this->user->url . $this->wsdl . '?wsdl';

        return $this;
    }

    /**
     * @throws SoapException
     */
    public function request(string $method, array $params = []): mixed
    {
        try {
            $client = new \SoapClient($this->user->url . $this->wsdl . '?wsdl', $this->getOptions());

            $response = $client->{$method}($params);

            return $response->return ?? throw new SoapException('Нет ответа от 1C сервера');
        } catch (\Exception $e) {
            throw new SoapException($e->getMessage(), false);
        }
    }

    private function getOptions(): array
    {
        return [
            'login' => $this->user->login,
            'password' => $this->user->password,
            'exceptions' => true,
            'encoding' => 'UTF-8',
            'keep_alive' => false,
            'trace' => true,
            'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
            'connection_timeout' => 600,
            'cache_wsdl' => WSDL_CACHE_NONE,
        ];
    }
}
