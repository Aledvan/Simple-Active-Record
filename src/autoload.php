<?php

spl_autoload_register('autoloader');

function autoloader($className)
{
    $path = strtr($className, '\\', '/') . '.php';
    if (is_file($path)) {
        include_once($path);
    } else if (is_file($path = __DIR__ . "/$path")) {
        include_once($path);
    } else {
        error_log(sprintf('%s not included: %s', $className, $path));
    }
}