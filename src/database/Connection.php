<?php

namespace Src\Database;

use Src\Middlewares\Exceptions\DbException;
use Config\DbConfig;

class Connection
{
    private static $instance = null;
    private $dbh;
   
    private function __construct() 
    {
        $dsn = "mysql:host=" . DbConfig::DB_HOST . ";dbname=" . DbConfig::DB_NAME . ";charset=utf8mb4";

        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->dbh = new \PDO($dsn, DbConfig::DB_USER, DbConfig::DB_PASS, $options);
        } catch (\PDOException $e) {
            throw new DbException($e, __LINE__);
        }
    }

    /**
     * @return self;
     */
    public static function getInstance() :self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->dbh;
    }
}