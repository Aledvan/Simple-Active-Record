<?php

namespace MsAmgbp\Src\Database;

use MsAmgbp\Src\Database\Connection;

class Query
{
    private $dbh;

    public function __construct()
    {
        $this->dbh = Connection::getInstance()->getConnection();
    }

    public function execute(string $sql, array $params, bool $fetchAll = true)
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