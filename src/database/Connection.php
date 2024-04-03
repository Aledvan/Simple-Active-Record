<?php
declare(strict_types=1);

namespace Src\Database;

use PDO;
use PDOException;
use Src\config\DbConfig;
use Src\Exception\DbException;
use Src\Helper;
use Src\Database\Driver;

class Connection
{
    private static ?PDO $instance = null;

    /**
     * Get an instance of a database connection
     *
     * @return PDO
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            self::$instance = self::createConnection();
        }
        return self::$instance;
    }

    /**
     * Create new connection with database
     *
     * @return PDO
     */
    private static function createConnection(): PDO
    {
        $activeDbDriver = Helper::getValueFromArray(DbConfig::DB_DRIVER);
        $dsn = Driver::matchDriver($activeDbDriver);

        return new PDO($dsn, DbConfig::DB_USER, DbConfig::DB_PASS, DbConfig::DB_OPTIONS);
    }

    /**
     * Close connection
     * 
     * @return void
     */
    public static function close(): void
    {
        self::$instance = null;
    }
}