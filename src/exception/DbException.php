<?php
declare(strict_types=1);

namespace Src\Exception;

use Src\Logging\Logger;
use Src\Logging\LogLevel;

class DbException
{
    /**
     * @param object|array|string $errorData
     *
     * @return void
     */
    public static function setError(object|array|string $errorData): void
    {
        if (is_array($errorData)) {
            $errorData = json_encode($errorData, JSON_PRETTY_PRINT);
        }

        Logger::error(
            LogLevel::ERROR,
            '[ '.date('Y-m-d H:i:s').' | ' . LogLevel::ERROR . '  ]' . PHP_EOL,
            $errorData
        );
    }
}