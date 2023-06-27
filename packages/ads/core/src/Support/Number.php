<?php

namespace Ads\Core\Support;

class Number
{
    public static function onlyNumbers(?string $value): ?string
    {
        $numbers = preg_replace('![^0-9]+!', '', $value);

        return $numbers ?: null;
    }
}
