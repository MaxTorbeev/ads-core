<?php

namespace Ads\Core\Exceptions\User;

class UserCanNotUpdatedException extends \Exception
{
    protected $message = 'Пользователь не найден';

    protected $code = 403;
}
