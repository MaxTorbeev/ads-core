<?php

namespace Ads\Core\Contracts\Logging;

interface LoggerDriver
{
    /**
     * Создает запись лога c данными запроса и возращает еше id
     *
     * @param array $data
     * @return int
     */
    public function request(array $data): int;

    /**
     * Записывает в лог ответ
     *
     * @param array $data
     * @return mixed
     */
    public function response(array $data);
}
