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

        $this->log = (new Log())->fill($this->formatRequest($parameters));

        return $this;
    }

    public function response(LoggerParametersDto $parameters): self
    {
        // Исключить из логирование по http статусам
        if (in_array($parameters->getResponseCode(), config('ads-logger.except_code_statuses', []))) {
            return $this;
        }

        // Если в логах указано записывать только ошибку и в статус-коде ошибки нет, то не записывать лог
        if ($this->isOnlyError() && !$this->isErrorByCode($parameters->getResponseCode())) {
            return $this;
        }

        if (!$this->canCreateLog($parameters)) {
            return $this;
        }

        $this->setExceptedFields($parameters, 'response');

        $responseData = $this->eraseFieldsWithEllipsis($this->exceptedFields['response'], $parameters->getResponse());

        $this->log->fill(
            array_merge(
                $this->formatRequest($parameters),
                [
                    'response' => $responseData,
                    'executing_time' => $parameters->getExecutingTime(),
                    'user_id' => $parameters->getUser()?->id,
                    'response_code' => $parameters->getResponseCode()
                ]
            )
        );

        $this->log->save();

        return $this;
    }

    private function setExceptedFields(LoggerParametersDto $parameters, string $scope = 'request'): void
    {
        $this->exceptedFields[$scope] = [];

        $routeName = Route::currentRouteName();

        $config = config('ads-logger.except')[$routeName]
            ?? config('ads-logger.except')[$parameters->getUri()]
            ?? null;

        if (!$config)
            return;

        if (in_array('onlyErrors', $config)) {
            $this->exceptedFields['onlyErrors'] = $config['onlyErrors'];
        } else if ($config[$scope] ?? false) {
            $this->exceptedFields[$scope] = $config[$scope];
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
            'method' => $parameters->getMethod(),
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
     * @param array|string|bool $fields - поля, которые требуется исключить из логирования.
     * Если пришло значение false, в лог не будет записано ни одно поле
     * @param array|stdClass|string $data
     * @return array|string|null
     */
    private function eraseFieldsWithEllipsis(array|string|bool $fields, array|stdClass|string $data): array|string|null
    {
        if ($fields === false) {
            return null;
        }

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

    public function isOnlyError(): bool
    {
        return $this->exceptedFields['onlyErrors'] ?? false;
    }

    public function isErrorByCode(int $code): bool
    {
        return in_array($code, range(400, 599));
    }
}
