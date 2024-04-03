<?php

namespace Src\Database;

use Src\Config\DbConfig;

abstract class Driver
{
    public static function matchDriver($driver)
    {
        return match($activeDbDriver) {
            'pgsql'     => self::getPostgreSQLDriver();
            'cubrid'    => self::getCubridDriver();
            'dblib'     => self::getpostgreSQLDriver();
            'firebird'  => self::getFirebirdDriver();
            'ibm'       => self::getIbmDriver();
            'informix'  => self::getInformixDriver();
            'sqlsrv'    => self::getMsSQLDriver();
            'oci'       => self::getOracleDriver();
            'odbc'      => self::getpostgreSQLDriver();
            'sqlite'    => self::getpostgreSQLDriver();
            default     => self::getMySQLDriver();
        }
    }

    private static function receiveDriver($driver)
    {
        return sprintf('%s:host=%s;port=%s;dbname=%s;charset=%s',
            $driver,
            DbConfig::DB_HOST,
            DbConfig::DB_PORT,
            DbConfig::DB_NAME,
            DbConfig::DB_CHARSET
        );
    }

    public static function getMySQLDriver()
    {
        return self::receiveDriver('mysql');
    }

    public static function getPostgreSQLDriver()
    {
        return self::receiveDriver('pgsql');
    }
}