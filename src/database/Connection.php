<?php

namespace Src\Database;

use Src\Middlewares\Exceptions\DbException;
use Config\DbConfig;
use PDO;
use PDOException;

class Connection
{
    private static $instance = null;
    private $dbh;

    private function __construct(){}

    /**
     * @return PDO
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            self::$instance = self::getConnection();
        }
        return self::$instance;
    }

    /**
     * @return PDO
     */
    protected static function getConnection(): PDO
    {
        $dsn = sprintf('%s:host=%s;port=%s;dbname=%s;charset=%s',
            self::getDbDriverValue(),
            DbConfig::DB_HOST,
            DbConfig::DB_PORT,
            DbConfig::DB_NAME,
            DbConfig::DB_CHARSET
        );

        try {
            $dbh = new PDO($dsn, DbConfig::DB_USER, DbConfig::DB_PASS, DbConfig::DB_OPTIONS);

            return $dbh;
        } catch (PDOException $e) {
            throw new DbException($e, __LINE__);
        }
    }

    private static function getDbDriverValue()
    {
        return array_search(true, DbConfig::DB_DRIVER);
    }
}