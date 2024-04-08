<?php
declare(strict_types=1);

namespace Src\Database;

use PDO;
use PDOException;
use PDOStatement;
use Src\Exception\DbException;
use Src\Logging\Logger;
use Src\Logging\LogLevel;

class Query extends Connection
{
    private static ?PDO $dbh = null;

    protected static function getDbh(): PDO
    {
        if (!isset(self::$dbh)) {
            self::$dbh = Connection::getInstance();
        }

        return self::$dbh;
    }

    /**
     * Execute Query In Database
     *
     * @param string $sql
     * @param array $params
     * @param bool $fetchAll
     *
     * @return array|bool|null
     */
    protected static function executeQuery(string $sql, array $params = [], bool $fetchAll = true): bool|array|null
    {
        try {
            $stmt = self::getDbh()->prepare($sql);
            self::bindParams($stmt, $params);
            $result = $stmt->execute();
            if (stripos(trim($sql), 'SELECT') === 0) {
                return $fetchAll
                    ? ($stmt->fetchAll() ?: null)
                    : ($stmt->fetch() ?: null);
            }

            $data = [
                'dateTime' => date('Y-m-d H:i:s'),
                'sql' => $sql,
                'params' => $params,
                'result' => $result ?? null,
                'fetchAll' => $fetchAll ?? null
            ];
            Logger::debug(LogLevel::DEBUG, '', $data);

            return $result;
        } catch (PDOException $e) {
            $errorData = [
                'dateTime' => date('Y-m-d H:i:s'),
                'message' => $e->getMessage(),
                'sql' => $sql,
                'params' => $params,
                'result' => $result ?? null,
                'fetchAll' => $fetchAll ?? null
            ];
            DbException::setError($errorData);
            return false;
        }
    }

    /**
     * Bind parameters to a PDOStatement
     *
     * @param PDOStatement $stmt
     * @param array $params
     *
     * @return void
     */
    private static function bindParams(PDOStatement $stmt, array $params): void
    {
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
    }
}