<?php

namespace Ads\Core\Support;

class Phone
{
    public static function verifyPhoneNumber($phone): bool
    {
        if (!$phone || strlen(Number::onlyNumbers($phone)) !== 11) {
            return false;
        }

        return true;
    }

    public static function applyPhoneNumberMask($phone): string
    {
        $maskedPhone = '';
        $numbersCounter = 0;
        for ($i = 0; $i < strlen($phone); $i++){
            if (is_numeric($phone[$i])) {
                $numbersCounter++;
            }
            $maskedPhone .= (is_numeric($phone[$i]) && $numbersCounter > 4 && $numbersCounter <= 9) ? '*' : $phone[$i];
        }

        return $maskedPhone;
    }

    public static function isUserPhoneValid($user, $hierarchical = false): bool
    {
        if (!self::verifyPhoneNumber($user->phone)) {
            return false;
        }

        if ($hierarchical) {
            foreach($user->children() as $subAgent) {
                if (!self::verifyPhoneNumber($subAgent->phone)) {
                    return false;
                }
            }
        }

        return true;
    }
}
