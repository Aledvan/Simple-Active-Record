<?php

namespace Src\Config;

use PDO;

abstract class DbConfig
{
    CONST DB_HOST = "localhost";
    CONST DB_NAME = "db";
    CONST DB_PORT = 3306;
    CONST DB_USER = "root";
    CONST DB_PASS = "";
    CONST DB_CHARSET = "utf8";
    CONST DB_DRIVER = [
        'mysql'     => true,
        'pgsql'     => false,
        'mssql'     => false,
        'sqlsrv'    => false,
    ];

    CONST DB_OPTIONS = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
}