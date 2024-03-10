<?php

namespace Src\Database;

use Src\Database\Connection;

class Query extends Connection
{
    private static \PDO $dbh;

    public function __construct()
    {
        self::$dbh = Connection::init();
    }

    /**
     * @param string $sql
     * @param array $params
     * @param bool $fetchAll = true
     * @return array|bool|null
     */
    protected static function execute(string $sql, array $params, bool $fetchAll = true): array|bool|null
    {
        $stmt = self::$dbh->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $success = $stmt->execute();
        if (stripos(trim($sql), 'SELECT') === 0) {
            return $fetchAll ? ($stmt->fetchAll() ?: null) : ($stmt->fetch() ?: null);
        }

        return $success;
    }
}