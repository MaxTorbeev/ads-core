<?php

namespace Ads\WsdClient\Services\Clients;

use Ads\Logger\Contracts\Logging\HttpLogger;
use Ads\Logger\Enums\LogTypes;
use Ads\Logger\Services\Logger\LoggerParametersDto;
use Ads\WsdClient\Exceptions\SoapException;
use Ads\WsdClient\Models\UserWs;
use Ads\WsdClient\Services\Logger\WsdlClientLogger;
use Illuminate\Support\Facades\Auth;

class WsdlClient
{
    private UserWs $user;

    private string $wsdl;

    private WsdlClientLogger $logger;

    public function __construct(?UserWs $user, WsdlClientLogger $logger)
    {
        $this->logger = $logger;

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
        $this->wsdl = $this->user->url . $wsdl . '?wsdl';

        return $this;
    }


    /**
     * @throws SoapException
     */
    public function request(string $method, array $params = []): mixed
    {
        $loggerParams = (new LoggerParametersDto())
            ->setUser($this->user->user)
            ->setUri($this->wsdl)
            ->setRequest($this->getOptions())
            ->setType(LogTypes::SOAP->value);

        try {

            $this->logger->request(
                $loggerParams
            );

            $client = new \SoapClient($this->wsdl, $this->getOptions());

            $response = $client->{$method}($params);

            $this->logger->response(
                $loggerParams->setResponse($response)
            );

            return $response->return ?? throw new SoapException('Нет ответа от 1C сервера');
        } catch (\Exception $e) {

            $this->logger->response(
                $loggerParams->setResponse($e->getMessage())
            );

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
