<?php
declare(strict_types=1);

namespace Src\Logging;

class Logger extends LogWriter implements iLogger
{
    /**
     * @param string $logLevel
     * @param string $message
     * @param array|object|string $context
     *
     * @return void
     */
    public static function error(string $logLevel, string $message, array|object|string $context): void
    {
        LogWriter::setLog($logLevel, $message, $context);
    }

    /**
     * @param string $logLevel
     * @param string $message
     * @param array|object|string $context
     *
     * @return void
     */
    public static function debug(string $logLevel, string $message, array|object|string $context): void
    {
        LogWriter::setLog($logLevel, $message, $context);
    }
}