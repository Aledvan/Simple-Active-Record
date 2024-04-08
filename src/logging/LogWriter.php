<?php
declare(strict_types=1);

namespace Src\Logging;

use Src\Config\LogConfig;

class LogWriter
{
    /**
     * @param string $logLevel
     * @param string $message
     * @param array|object|string $context
     *
     * @return void
     */
    protected static function setLog(string $logLevel, string $message, array|object|string $context): void
    {
        if ($logLevel == LogLevel::ERROR) {
            $dirLog = LogConfig::DB_ERROR_LOG_DIR;
        } else {
            $dirLog = LogConfig::DB_QUERY_LOG_DIR;
        }
        if(!file_exists($dirLog)) {
            mkdir($dirLog, 0777, true);
        } else {
            $fullPath = $dirLog . date('Y-m-d') . '.log';
            file_put_contents($fullPath, $message . PHP_EOL . $context . PHP_EOL, FILE_APPEND);
        }
    }
}