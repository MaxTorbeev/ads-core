<?php

namespace Ads\Core\Exceptions\User;

class UserPasswordInvalidException extends \Exception
{
    protected $message = 'Неверный логин или пароль';

    protected $code = 401;
}
