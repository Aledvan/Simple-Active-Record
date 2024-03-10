<?php


namespace Src\Helpers;

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
}