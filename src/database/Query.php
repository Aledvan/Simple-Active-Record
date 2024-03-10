<?php

namespace Src\Database;

use Src\Database\Connection;

class Query extends Connection
{
    private $dbh;

    public function __construct()
    {
        $this->dbh = Connection::getConnection();
    }

    protected static function execute(string $sql, array $params, bool $fetchAll = true)
    {
        $stmt = $this->dbh->prepare($sql);
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