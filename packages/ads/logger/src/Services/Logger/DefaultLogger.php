<?php

namespace Ads\Logger\Services\Logger;

use Ads\Logger\Contracts\Logging\LoggerDriver;
use Ads\Logger\Models\Log;
use Illuminate\Support\Facades\Route;

class DefaultLogger implements LoggerDriver
{
    private Log $log;

    public function request(LoggerParametersDto $request): self
    {
        $this->log = Log::create($this->formatRequest($request));

        return $this;
    }

    public function response(LoggerParametersDto $request): self
    {
        $this->log->update([
            'response' => $request->getResponse(),
            'executing_time' => $request->getExecutingTime()
        ]);

        return $this;
    }

    private function formatRequest(LoggerParametersDto $request): array
    {
        $routeName = Route::currentRouteName();

        $exceptedFields = config('core.logging')['except'][$routeName]
            ?? config('logging')['fields_exclusion'][$request->getUri()]
            ?? null;

        $requestData = $exceptedFields
            ? $this->eraseFieldsWithEllipsis($exceptedFields, $request->getRequest())
            : $request->getRequest();

        return [
            'uri' => $request->getUri(),
            'user_id' => $request->getUser()?->id,
            'request' => $requestData,
            'type' => $request->getType(),
        ];
    }

    /**
     * Затереть значения полей многоточием.
     *
     * @param string|array $fields
     * @param array $data
     * @return array
     */
    private function eraseFieldsWithEllipsis(array|string $fields, array $data): array
    {
        if (!is_array($fields)) return $data;

        foreach ($fields as $field) {
            if (data_get($data, $field)) {
                data_set($data, $field, '...');
            }
        }

        return $data;
    }
}
