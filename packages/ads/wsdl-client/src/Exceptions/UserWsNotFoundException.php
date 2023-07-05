<?php

namespace Ads\WsdlClient\Exceptions;

use \Exception;

class UserWsNotFoundException extends Exception
{
    protected $message = 'Нет пользовательских данных для соединения с Web сервисом';

    protected $code = 403;
}
