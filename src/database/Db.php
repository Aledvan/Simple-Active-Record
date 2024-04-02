<?php
declare(strict_types=1);

namespace Src\Database;

use Exception;
use PDOException;
use Src\Exception\DbException;
use Src\Helper;
use Src\Database\Interfaces\iDb;

class Db extends Query implements iDb
{
    /**
     * Create new database or table
     *
     * @param string $entity
     * @param array $options
     *
     * @return bool
     * @throws Exception
     */
    public static function create(string $entity, array $options): bool
    {
        if (empty($entity) || empty($options)) {
            throw new Exception('Empty database/table or options');
        }

        $isAssociativeArray = Helper::checkAssociativeArray($options);
        if ($isAssociativeArray) {
            $isCreateDb = $options['isCreateDb'] ?? false;
            if ($isCreateDb) {
                return self::createDatabase($entity);
            } else {
                return self::createTable($entity, $options);
            }
        } else {
            throw new Exception('Invalid options format. Expecting an associative array');
        }
    }

    /**
     * Create new database
     *
     * @param string $database
     *
     * @return bool
     */
    private static function createDatabase(string $database): bool
    {
        $sql = "CREATE DATABASE $database";
        
        try {
            return self::executeQuery($sql);
        } catch (PDOException $e) {
            DbException::setError($e, 100);
            return false;
        }
    }

    /**
     * Create new table
     *
     * @param string $table
     * @param array $options
     *
     * @return bool
     * @throws PDOException
     * @throws Exception
     */
    private static function createTable(string $table, array $options): bool
    {
        try {
            $sql = QueryBuilder::prepareDataForCreateTableQuery($table, $options);
            return self::executeQuery($sql);
        } catch (PDOException $e) {
            DbException::setError($e, 101);
            return false;
        }
    }

    /**
     * Use new database
     *
     * @param string $database
     *
     * @return bool
     * @throws Exception
     */
    public static function use(string $database): bool
    {
        try {
            if (empty($database)) {
                throw new Exception('Empty database name');
            }

            $sql = "USE $database";
            return (bool)self::executeQuery($sql);
        } catch (PDOException $e) {
            DbException::setError($e, 102);
            return false;
        }
    }

    /**
     * Drop database or table
     *
     * @param string $entity
     * @param array $options
     *
     * @return bool
     * @throws Exception
     */
    public static function drop(string $entity, array $options): bool
    {
        if (empty($entity) || empty($options)) {
            throw new Exception('Empty database/table or options');
        }

        $isAssociativeArray = Helper::checkAssociativeArray($options);
        if ($isAssociativeArray) {
            $isDropDb = $options['isDropDb'] ?? false;
            if ($isDropDb) {
                return self::dropDatabase($entity);
            } else {
                return self::dropTable($entity);
            }
        } else {
            throw new Exception('Invalid options format. Expecting an associative array');
        }
    }

    /**
     * Drop database
     *
     * @param string $database
     *
     * @return bool
     */
    private static function dropDatabase(string $database): bool
    {
        try {
            $sql = "DROP DATABASE $database";
            return self::executeQuery($sql);
        } catch (PDOException $e) {
            DbException::setError($e, 103);
            return false;
        }
    }

    /**
     * Drop table
     *
     * @param string $table
     *
     * @return bool
     */
    private static function dropTable(string $table): bool
    {
        try {
            $sql = "DROP TABLE $table";
            return self::executeQuery($sql);
        } catch (PDOException $e) {
            DbException::setError($e, 104);
            return false;
        }
    }

    /**
     * Truncate table
     *
     * @param string $table
     *
     * @return bool
     * @throws Exception
     * @throws PDOException
     */
    public static function truncate(string $table): bool
    {
        try {
            if (empty($database)) {
                throw new Exception('Empty table name');
            }

            $sql = "TRUNCATE TABLE $table";
            return self::executeQuery($sql);
        } catch (PDOException $e) {
            DbException::setError($e, 104);
            return false;
        }
    }

    /**
     * Insert new lines and values in table
     *
     * @param string $table
     * @param array $options
     *
     * @return bool
     * @throws PDOException
     * @throws Exception
     */
    public static function insert(string $table, array $options): bool
    {
        try {
            if (empty($table) || empty($options)) {
                throw new Exception('Empty table name or options');
            }

            $isAssociativeArray = Helper::checkAssociativeArray($options);
            if ($isAssociativeArray) {
                $data = QueryBuilder::prepareDataForInsertQuery($table, $options);
                $sql = $data['sql'];
                $params = $data['params'];

                return (bool)self::executeQuery($sql, $params);
            } else {
                throw new Exception('Invalid options format. Expecting an associative array');
            }
        } catch (PDOException $e) {
            DbException::setError($e, 104);
            return false;
        }
    }

    /**
     * Rename table
     *
     * @param string $oldTable
     * @param string $newTable
     *
     * @return bool
     * @throws Exception
     */
    public static function rename(string $oldTable, string $newTable): bool
    {
        try {
            if (empty($oldTable) || empty($newTable)) {
                throw new Exception('Empty old or new table');
            }

            $sql = "RENAME TABLE $oldTable TO $newTable";
            return (bool)self::executeQuery($sql);
        } catch (PDOException $e) {
            error_log('Error in Db Insert Method: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Select values from table
     *
     * @param string $table
     * @param array $options
     *
     * @return ?array
     */
    public static function select(string $table, array $options): ?array
    {
        try {
            if (empty($table) || empty($options)) {
                throw new Exception('Empty table name or options');
            }

            $data = QueryBuilder::prepareDataForSelectQuery($table, $options);
            $sql = $data['sql'];
            $params = $data['params'];
            $fetchAll = $data['fetchAll'];
            return self::executeQuery($sql, $params, $fetchAll);
        } catch (PDOException $e) {
            error_log('Error in Db Select Method: ' . $e->getMessage());
            return NULL;
        }
    }

    /**
     * Update values in table
     *
     * @param string $table
     * @param array $options
     *
     * @return bool
     */
    public static function update(string $table, array $options = []): bool
    {
        try {
            $setParts = [];
            $where = $options['where'] ?? null;
            $setParams = $options['params'] ?? [];
            $whereParams = $options['whereParams'] ?? [];
            foreach ($setParams as $setKey => $setValue) {
                $setKey = ltrim($setKey, ':');
                $setParts[] = "$setKey = :$setKey";
                $params[":$setKey"] = $setValue;
            }
            $setString = implode(', ', $setParts);
            $sql = "UPDATE $table SET $setString";
            if ($where) {
                $sql .= " WHERE $where";
                $params = array_merge($params, $whereParams);
            }

            return (bool)self::executeQuery($sql, $params);
        } catch (PDOException $e) {
            error_log('Error in Db Update Method: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete lines from table
     *
     * @param string $table
     * @param array $options
     *
     * @return bool
     */
    public static function delete(string $table, array $options = []): bool
    {
        try {
            $where = $options['where'] ?? null;
            $params = $options['params'] ?? [];
            $sql = "DELETE FROM $table";
            if ($where) {
                $sql .= " WHERE $where";
            }

            return (bool)self::executeQuery($sql, $params);
        } catch (PDOException $e) {
            error_log('Error in Db Delete Method: ' . $e->getMessage());
            return false;
        }
    }
}