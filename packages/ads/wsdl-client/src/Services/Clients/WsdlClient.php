<?php

namespace Ads\WsdlClient\Services\Clients;

use Ads\Core\Services\User\AuthService;
use Ads\Logger\Enums\LogTypes;
use Ads\Logger\Services\Logger\LoggerParametersDto;
use Ads\WsdlClient\Exceptions\SoapException;
use Ads\WsdlClient\Exceptions\UserWsNotFoundException;
use Ads\WsdlClient\Models\UserWs;
use Ads\WsdlClient\Services\Logger\WsdlClientLogger;
use SoapClient;
use SoapFault;

class WsdlClient
{
    private UserWsDto $user;

    private AuthService $authService;

    private string $wsdl;

    private WsdlClientLogger $logger;

    public function __construct(WsdlClientLogger $logger, AuthService $authService)
    {
        $this->logger = $logger;
        $this->authService = $authService;

        if ($userWs = $this->authService->user()?->userWs) {
            $this->user = UserWsDto::fromUserWs($userWs);
        }
    }

    public function setUser(UserWs $user): self
    {
        $this->user = UserWsDto::fromUserWs($user);

        return $this;
    }

    /**
     * @throws UserWsNotFoundException
     */
    public function setWsdl(string $wsdl): self
    {
        if (!$this->user) {
            throw new UserWsNotFoundException();
        }

        $this->wsdl = $this->user->getUrl() . $wsdl . '?wsdl';

        return $this;
    }

    /**
     * @throws SoapException|\SoapFault
     */
    public function request(string $method, array $params = []): mixed
    {
        $loggerParams = (new LoggerParametersDto())
            ->setUser($this->user->getUser())
            ->setUri($this->wsdl)
            ->setRequest($params)
            ->setType(LogTypes::SOAP->value);

        $this->logger->request($loggerParams);

        try {
            $client = new SoapClient($this->wsdl, $this->getOptions());

            $response = $client->{$method}($params);

            $this->logger->response(
                $loggerParams->setResponse($response)
            );

            $this->handleResponseError($response, $loggerParams);

            return $response->return ?? throw new SoapException('Нет ответа от 1C сервера');

        } catch (SoapFault $e) {
            $this->logger->response(
                $loggerParams->setResponse($e->getMessage())
            );

            throw new SoapException($e->getMessage(), false);
        }
    }

    private function getOptions(): array
    {
        return [
            'login' => $this->user->getLogin(),
            'password' => $this->user->getPassword(),
            'exceptions' => true,
            'encoding' => 'UTF-8',
            'keep_alive' => false,
            'trace' => true,
            'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
            'connection_timeout' => 600,
            'cache_wsdl' => WSDL_CACHE_NONE,
        ];
    }

    /**
     * @throws SoapException
     */
    protected function handleResponseError($response, LoggerParametersDto $parametersDto)
    {
        if (!empty($response->Errors)) {
            foreach ($response->Errors as $error) {
                if ($error->Status === 'Ошибка') {

                    $parametersDto->setResponseCode(500);

                    $this->logger->response($parametersDto);

                    throw new SOAPException($error->Error, true);
                }
            }
        }

        if (!empty($response->Error)) {
            if (is_array($response->Error)) {
                foreach ($response->Error as $error) {
                    if ($error->Status === 'Ошибка') {

                        $parametersDto->setResponseCode(500);
                        $this->logger->response($parametersDto);

                        throw new SOAPException($error->Error, true);
                    }
                }
            } else if (trim($response->Error) !== '') {
                $this->logger->response($this->getResponseData($logData));

                throw new SOAPException($response->Error, true);
            }
        }
    }
}
