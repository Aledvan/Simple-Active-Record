<?php
declare(strict_types=1);

namespace Src\Exception;

use Src\Logging\Logger;

class DbException
{
    /**
     * @param mixed $errorData
     *
     * @return void
     */
    public static function setError(mixed $errorData): void
    {
        Logger::setLog('[ '.date('Y-m-d H:i:s').' | Error ]' . PHP_EOL . $errorData );
    }
}