<?php

namespace Src\Database;

use Src\Config\DbConfig;

abstract class Driver
{
    public static function matchDriver($driver)
    {
        return match($driver) {
            'pgsql'     => self::getPostgreSQLDriver(),
            'mssql'     => self::getMicrosoftSQLServerDriver(),
            'sqlsrv'    => self::getSQLServerDriver(),
            default     => self::getMySQLDriver(),
        };
    }

    private static function receiveDriverDefault($driver): string
    {
        return sprintf('%s:host=%s;port=%s;dbname=%s;charset=%s',
            $driver,
            DbConfig::DB_HOST,
            DbConfig::DB_PORT,
            DbConfig::DB_NAME,
            DbConfig::DB_CHARSET
        );
    }

    private static function receiveDriverSQLServer($driver): string
    {
        return sprintf('%s:Server=%s;%s;Database=%s',
            $driver,
            DbConfig::DB_HOST,
            DbConfig::DB_PORT,
            DbConfig::DB_NAME
        );
    }

    private static function getMySQLDriver(): string
    {
        return self::receiveDriverDefault('mysql');
    }

    private static function getPostgreSQLDriver(): string
    {
        return self::receiveDriverDefault('pgsql');
    }

    private static function getSQLServerDriver(): string
    {
        return self::receiveDriverSQLServer('sqlsrv');
    }

    private static function getMicrosoftSQLServerDriver(): string
    {
        return self::receiveDriverDefault('mssql');
    }
}