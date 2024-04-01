<?php
declare(strict_types=1);

namespace Src\Database;

use PDO;
use PDOException;
use Src\config\DbConfig;
use Src\Helper;

class Connection
{
    private static ?PDO $instance = null;

    /**
     * Get an instance of a database connection
     *
     * @return PDO
     * @throws PDOException
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
     * @throws PDOException
     */
    private static function createConnection(): PDO
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

    /**
     * Close connection
     */
    public static function close(): void
    {
        self::$instance = null;
    }
}