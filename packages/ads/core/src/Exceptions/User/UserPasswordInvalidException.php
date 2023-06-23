<?php

namespace Ads\Core\Exceptions\User;

class UserPasswordInvalidException extends \Exception
{
    protected $message = 'Неверный email или пароль';
}
