<?php

namespace Ads\Core\Support;

class Arr extends \Illuminate\Support\Arr
{
    /**
     * Ключ минимального значения массива.
     *
     * @param array $array
     * @return int|mixed|string|null
     */
    public static function minimumValueKey(array $array)
    {
        if (count($array) === 0)
            return null;

        return count($array) > 1
            ? array_keys($array, min($array))[0]
            : array_key_first($array);
    }

    /**
     * Убирает пробелы в начале и в конце строки у каждого элемента массива.
     * Если элемент итерации - ассоциативный массив, необходимо передать ключ.
     *
     * @param array $array
     * @param string|null $key
     * @return array|string[]
     */
    public static function trimAllValues(array $array, string $key = null): array
    {
        foreach ($array as &$element) {
            if ($key) {
                $element = (array)$element;

                $element[$key] = trim($element[$key]);
            } else {
                $element = trim($element);
            }
        }

        return $array;
    }

    /**
     * Очищает массив от дубликатов.
     * Если элемент итерации - ассоциативный массив, необходимо передать ключ.
     *
     * @param array $array
     * @param string|null $key
     * @return array
     */
    public static function uniqueValues(array $array, string $key = null): array
    {
        $hash = [];
        $response = [];

        foreach ($array as $index => $element) {
            if ($key) {
                $element = (array)$element;
                $elemIndex = $element[$key];
            } else {
                $elemIndex = $element;
            }

            if (!isset($hash[$elemIndex])) {
                $response[] = $element;
            }

            $hash[$elemIndex] = true;
        }

        return $response;
    }

    /**
     * Получить порядковый номер элемента массива по его ключу.
     *
     * @param array $haystack
     * @param $needle
     * @return int
     */
    public static function serialNumberArrayEl(array $haystack, $needle): int
    {
        $sn = 0;

        foreach (array_keys($haystack) as $key) {
            if ($key === $needle) {
                return $sn;
            }
            $sn++;
        }

        return $sn;
    }

    /**
     * Проверяет два массива на эквивалентность ключей и значений.
     *
     * @param array $array1
     * @param array $array2
     * @return bool
     */
    public static function isEqual(array $array1, array $array2): bool
    {
        if (is_object($array1)) {
            $array1 = (array)$array1;
        }

        if (is_object($array2)) {
            $array2 = (array)$array2;
        }

        if (!is_array($array1) || !is_array($array2)) {
            return $array1 === $array2;
        }

        foreach ($array1 as $key => $value) {
            if (!isset($array2[$key]) || !static::isEqual($array1[$key], $array2[$key])) {
                return false;
            }
        }

        foreach ($array2 as $key => $value) {
            if (!isset($array1[$key]) || !static::isEqual($array1[$key], $array2[$key])) {
                return false;
            }
        }

        return true;
    }
}
