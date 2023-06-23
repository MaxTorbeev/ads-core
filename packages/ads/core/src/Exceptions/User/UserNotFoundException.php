<?php

namespace Ads\Core\Exceptions\User;

class UserNotFoundException extends \Exception
{
    protected $message = 'Пользователь не найден';

    protected $code = 401;
}
