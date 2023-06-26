<?php

namespace Ads\Logger\Services\Logger;

use Ads\Logger\Contracts\Logging\HttpLogger;
use Ads\Logger\Models\Log;
use Illuminate\Support\Facades\Route;
use stdClass;

abstract class AbstractDefaultLogger implements HttpLogger
{
    private Log $log;

    private array $exceptedFields;

    public function getModel(): Log
    {
        return $this->log;
    }

    public function request(LoggerParametersDto $parameters): self
    {
        if (!$this->canCreateLog($parameters)) {
            return $this;
        }

        $this->setExceptedFields($parameters);


        $this->log = Log::create($this->formatRequest($parameters));

        return $this;
    }

    public function response(LoggerParametersDto $parameters): self
    {
        if (!$this->canCreateLog($parameters)) {
            return $this;
        }

        $this->setExceptedFields($parameters, 'response');

        $responseData = $this->eraseFieldsWithEllipsis($this->exceptedFields['response'], $parameters->getResponse());

        $this->log->update([
            'response' => $responseData,
            'executing_time' => $parameters->getExecutingTime(),
            'user_id' => $parameters->getUser()?->id,
            'response_code' => $parameters->getResponseCode()
        ]);

        return $this;
    }

    private function setExceptedFields(LoggerParametersDto $parameters, string $scope = 'request'): void
    {
        $this->exceptedFields[$scope] = [];

        $routeName = Route::currentRouteName();

        if (isset (config('ads-logger.except')[$routeName]) && isset(config('ads-logger.except')[$routeName][$scope])) {
            $this->exceptedFields[$scope] = config('ads-logger.except')[$routeName][$scope];
        } else if (isset(config('ads-logger.except')[$parameters->getUri()][$scope])) {
            $this->exceptedFields[$scope] = config('ads-logger.except')[$parameters->getUri()][$scope];
        }
    }

    /**
     * Formatting log request data.
     *
     * @param LoggerParametersDto $parameters
     * @return array
     */
    private function formatRequest(LoggerParametersDto $parameters): array
    {
        $requestData = $this->exceptedFields['request']
            ? $this->eraseFieldsWithEllipsis($this->exceptedFields['request'], $parameters->getRequest())
            : $parameters->getRequest();

        return [
            'uri' => $parameters->getUri(),
            'user_id' => $parameters->getUser()?->id,
            'ip' => $parameters->getIp(),
            'request' => $requestData,
            'type' => $parameters->getType(),
        ];
    }

    /**
     * Затереть значения полей многоточием.
     *
     * @param array|string $fields
     * @param array|stdClass|string $data
     * @return array|string
     */
    private function eraseFieldsWithEllipsis(array|string $fields, array|stdClass|string $data): array|string
    {
        if (!is_array($fields)) {
            return $data;
        }

        if ($data instanceof stdClass) {
            $data = (array)$data;
        }

        foreach ($fields as $field) {
            if (data_get($data, $field)) {
                data_set($data, $field, '...');
            }
        }

        return $data;
    }

    /**
     * Сверить с настройками, есть ли возможность использовать логгер
     *
     * @param LoggerParametersDto $parameters
     * @return bool
     */
    private function canCreateLog(LoggerParametersDto $parameters): bool
    {
        $routeName = Route::currentRouteName();
        $uri = $parameters->getUri();
        $configs = config('ads-logger.except');

        return (!isset($configs[$routeName]) || isset($configs[$routeName]) && $configs[$routeName] !== false)
            && (!isset($configs[$uri]) || isset($configs[$uri]) && $configs[$uri] !== false);
    }
}
