<?php

namespace Ads\Logger\Enums;

enum LogTypes: string
{
    case HTTP = 'http';
    case WEB = 'web';
    case API = 'api';
    case SOAP = 'soap';
    case SYSTEM = 'system';
}
