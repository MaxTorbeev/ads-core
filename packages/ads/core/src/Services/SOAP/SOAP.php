<?php

namespace Ads\Core\Services\SOAP;

use App\Models\User;
use App\Services\Logging\LoggerDriverInterface;

class SOAP
{
    public function __construct(LoggerDriverInterface $logDriver = null)
    {

    }

    public function call(string $wsdl, string $method, array $params = [], ?User $user = null, $handleErrors = true)
    {

    }
}
