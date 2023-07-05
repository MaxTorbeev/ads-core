<?php

namespace Ads\Core\Support;

use DateTime;
class Date
{
    public static function parseFormedDate($value, $dateFormat = 'Y-m-d')
    {
        $date = DateTime::createFromFormat('Y-m-d H:i', $value);
        if ($date !== FALSE) {
            $value = $date->format('Y-m-d\TH:i:sP');
        }

        return $value;
    }

    public static function parseDate($value, $dateFormat = 'Y-m-d')
    {
        $date = DateTime::createFromFormat('d.m.Y', $value);

        if ($date !== false) {
            $value = $date->format($dateFormat);
        }

        return $value;
    }

    public static function convertDates($data, $dateFormat = 'Y-m-d')
    {
        foreach ($data as &$value) {
            if (is_array($value)) {
                $value = self::convertDates($value);
            } else {
                $value = self::parseDate(self::parseFormedDate($value), $dateFormat);
            }
        }
        return $data;
    }
}
