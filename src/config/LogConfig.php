<?php

namespace Src\Config;

use PDO;

abstract class LogConfig
{
    CONST DB_QUERY_LOG_DIR = __DIR__ . '/../../log/errors/db-errors/';

    CONST DB_ERROR_LOG_DIR = __DIR__ . '/../../log/db-queries/';

    CONST DB_QUERY_LOGGING = true;
}