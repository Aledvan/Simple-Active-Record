<?php


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
}