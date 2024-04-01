<?php
declare(strict_types=1);

namespace Src;

class Helper
{
    /**
     * @param array $array
     * @return string
     */
    public static function getValueFromArray(array $array): string
    {
        return array_search(true, $array);
    }

    /**
     * @param array $array
     * @return bool
     */
    public static function checkAssociativeArray(array $array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }
}