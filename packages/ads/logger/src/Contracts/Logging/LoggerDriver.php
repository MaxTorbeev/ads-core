<?php

namespace Ads\Logger\Contracts\Logging;

use Ads\Logger\Models\Log;
use Ads\Logger\Services\Logger\LoggerParametersDto;

interface LoggerDriver
{
    public function getModel(): Log;

    /**
     * Создает лог запись c данными запроса и возвращает еше id
     *
     * @param LoggerParametersDto $parameters
     * @return LoggerDriver
     */
    public function request(LoggerParametersDto $parameters): self;

    /**
     * Записывает в лог ответ
     *
     * @param LoggerParametersDto $parameters
     * @return mixed
     */
    public function response(LoggerParametersDto $parameters): self;
}
