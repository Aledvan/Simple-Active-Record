<?php

namespace Config;

class DbConfig
{
    CONST DB_HOST = "localhost";
    CONST DB_NAME = "tochka-acquiring";
    CONST DB_PORT = 10600;
    CONST DB_USER = "tochka-acquiring";
    CONST DB_PASS = "Jtgw81![A1cdKW8J";
    CONST DB_CHARSET = "utf8mb4";
    CONST DB_DRIVER = [
        'mysql'     => true,
        'pgsql'     => false,
        'cubrid'    => false,
        'dblib'     => false,
        'firebird'  => false,
        'ibm'       => false,
        'informix'  => false,
        'sqlsrv'    => false,
        'oci'       => false,
        'odbc'      => false,
        'sqlite'    => false,
    ];

    CONST DB_OPTIONS = [
        \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::ATTR_EMULATE_PREPARES   => false,
    ];
}