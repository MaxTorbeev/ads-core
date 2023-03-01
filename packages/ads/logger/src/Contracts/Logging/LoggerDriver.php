<?php

namespace Ads\Logger\Contracts\Logging;

use Ads\Logger\Models\Log;
use Ads\Logger\Services\Logger\LoggerParametersDto;

interface LoggerDriver
{
    /**
     * Создает лог запись c данными запроса и возвращает еше id
     *
     * @param LoggerParametersDto $request
     * @return LoggerDriver
     */
    public function request(LoggerParametersDto $request): self;

    /**
     * Записывает в лог ответ
     *
     * @param LoggerParametersDto $request
     * @return mixed
     */
    public function response(LoggerParametersDto $request): self;
}
