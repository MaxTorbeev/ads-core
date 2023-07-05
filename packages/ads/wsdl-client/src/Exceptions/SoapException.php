<?php

namespace Ads\WsdlClient\Exceptions;

use Exception;

class SoapException extends Exception
{
    protected $message = 'Нет ответа от 1C сервера';
}
