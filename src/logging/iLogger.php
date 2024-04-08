<?php

namespace Src\Logging;

interface iLogger
{
    public static function error(string $logLevel, string $message, array|object|string $context): void;
    public static function debug(string $logLevel, string $message, array|object|string $context): void;
}