<?php

namespace Src\Database;

use Config\DbConfig;
use PDO;
use PDOException;
use Src\Helpers\Helper;

class Connection
{
    private static $instance = null;

    /**
     * @return PDO
     */
    public static function init(): PDO
    {
        if (self::$instance === null) {
            self::$instance = self::getConnection();
        }
        return self::$instance;
    }

    /**
     * @return PDO
     */
    private static function getConnection(): PDO
    {
        $dsn = sprintf('%s:host=%s;port=%s;dbname=%s;charset=%s',
            Helper::getValueFromArray(DbConfig::DB_DRIVER),
            DbConfig::DB_HOST,
            DbConfig::DB_PORT,
            DbConfig::DB_NAME,
            DbConfig::DB_CHARSET
        );

        try {
            return new PDO($dsn, DbConfig::DB_USER, DbConfig::DB_PASS, DbConfig::DB_OPTIONS);
        } catch (PDOException $e) {
            error_log('Database Connection Error: ' . $e->getMessage());
        }
    }
}