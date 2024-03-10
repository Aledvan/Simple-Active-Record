<?php

namespace Src\Database;

use PDO;
use Src\Database\Connection;

class Query extends Connection
{
    private static ?PDO $dbh = null;

    protected static function getDbh(): PDO
    {
        if (!isset(self::$dbh)) {
            self::$dbh = Connection::init();
        }

        return self::$dbh;
    }

    /**
     * @param string $sql
     * @param array $params
     * @param bool $fetchAll = true
     * @return array|bool|null
     */
    protected static function executeQuery(string $sql, array $params, bool $fetchAll = true)
    {
        $stmt = self::getDbh()->prepare($sql);
        self::bindParams($stmt, $params);
        $result = $stmt->execute();
        if (stripos(trim($sql), 'SELECT') === 0) {
            return $fetchAll
                ? ($stmt->fetchAll() ?: null)
                : ($stmt->fetch() ?: null);
        }

        return $result;
    }

    /**
     * @param PDOStatement $stmt
     * @param array $params
     */
    private static function bindParams(PDOStatement $stmt, array $params)
    {
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
    }
}