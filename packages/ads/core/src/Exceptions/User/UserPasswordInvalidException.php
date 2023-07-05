<?php

namespace Ads\Core\Exceptions\User;

use Ads\Core\Exceptions\AbstractCoreException;

class UserPasswordInvalidException extends AbstractCoreException
{
    protected $message = 'Неверный логин или пароль';

    protected $code = 401;
}
