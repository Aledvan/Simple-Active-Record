<?php
declare(strict_types=1);

namespace Src\Logging;

class Logger
{
    CONST LOG_DIR = __DIR__ . '/../../log/db-errors.log';

    /**
     * @param string $errorMessage
     *
     * @return void
    */
    public static function setLog(string $errorMessage): void
    {
        file_put_contents(self::LOG_DIR, $errorMessage, FILE_APPEND | LOCK_EX);
    }
}