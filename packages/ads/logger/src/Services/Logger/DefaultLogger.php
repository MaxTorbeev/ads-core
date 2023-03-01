<?php

namespace Ads\Logger\Services\Logger;

use Ads\Logger\Contracts\Logging\LoggerDriver;
use Ads\Logger\Models\Log;
use Illuminate\Support\Facades\Route;

class DefaultLogger implements LoggerDriver
{
    private Log $log;

    private array $exceptedFields;

    public function getModel(): Log
    {
        return $this->log;
    }

    public function request(LoggerParametersDto $parameters): self
    {
        $this->setExceptedFields($parameters, 'response');

        $this->log = Log::create($this->formatRequest($parameters));

        return $this;
    }

    public function response(LoggerParametersDto $parameters): self
    {
        $this->setExceptedFields($parameters, 'response');

        $responseData = $this->eraseFieldsWithEllipsis($this->exceptedFields['response'], $parameters->getResponse());

        $this->log->update([
            'response' => $responseData,
            'executing_time' => $parameters->getExecutingTime(),
            'user_id' => $parameters->getUser()?->id,
        ]);

        return $this;
    }

    private function setExceptedFields( LoggerParametersDto $parameters, string $scope = 'request'): void
    {
        $this->exceptedFields[$scope] = [];

        $routeName = Route::currentRouteName();

        if (isset (config('ads-logger.except')[$routeName]) && isset(config('ads-logger.except')[$routeName][$scope])) {
            $this->exceptedFields[$scope] = config('ads-logger.except')[$routeName][$scope];
        } else if (isset(config('ads-logger.except')['fields_exclusion']) && isset(config('ads-logger.except')['fields_exclusion'][$parameters->getUri()])) {
            $this->exceptedFields[$scope] = config('ads-logger.except')['fields_exclusion'][$parameters->getUri()];
        }
    }

    /**
     * Formatting log request data.
     *
     * @param LoggerParametersDto $request
     * @return array
     */
    private function formatRequest(LoggerParametersDto $parameters): array
    {
        $requestData = $this->exceptedFields
            ? $this->eraseFieldsWithEllipsis($this->exceptedFields, $parameters->getRequest())
            : $parameters->getRequest();

        return [
            'uri' => $parameters->getUri(),
            'user_id' => $parameters->getUser()?->id,
            'request' => $requestData,
            'type' => $parameters->getType(),
        ];
    }

    /**
     * Затереть значения полей многоточием.
     *
     * @param string|array $fields
     * @param array $data
     * @return array
     */
    private function eraseFieldsWithEllipsis(array|string $fields, array|string $data): array|string
    {
        if (!is_array($fields))
            return $data;

        foreach ($fields as $field) {
            if (data_get($data, $field)) {
                data_set($data, $field, '...');
            }
        }

        return $data;
    }
}
