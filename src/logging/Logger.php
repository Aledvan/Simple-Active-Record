<?php
declare(strict_types=1);

namespace Src\Logging;

use Src\Config\LogConfig;

class Logger
{
    /**
     * @param mixed $errorMessage
     *
     * @return void
    */
    public static function setLog(mixed $errorMessage): void
    {
        $dirLog = LogConfig::ERROR_LOG_DIR;
        if(!file_exists($dirLog)) {
            mkdir($dirLog, 0777, true);
        } else {
            $fullPath = $dirLog . date('Y-m-d') . '.log';
            file_put_contents($fullPath, $errorMessage . PHP_EOL, FILE_APPEND);
        }
    }
}