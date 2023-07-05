<?php

namespace Ads\Core\Exceptions\User;

use Ads\Core\Exceptions\AbstractCoreException;

class UserNotFoundException extends AbstractCoreException
{
    protected $message = 'Пользователь не найден';

    protected $code = 401;
}
