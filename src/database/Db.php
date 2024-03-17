<?php
declare(strict_types=1);

namespace Src\Database;

use PDOException;
use Src\Exception\DbException;

class Db extends Query
{
    /**
     * Create new database or table
     *
     * @param string $entity
     * @param array $options = []
     *
     * @return bool
     */
    public static function create(string $entity, array $options = []): bool
    {
        if (empty($options)) {
            return self::createDatabase($entity);
        } else {
            return self::createTable($entity, $options);
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
        try {
            $sql = "CREATE DATABASE $database";
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
     * @param array $fields
     *
     * @return bool
     */
    private static function createTable(string $table, array $fields): bool
    {
        try {
            $fieldDefinitions = '';
            foreach ($fields as $fieldName => $fieldType) {
                $fieldDefinitions .= "$fieldName $fieldType, ";
            }
            $fieldDefinitions = rtrim($fieldDefinitions, ', ');
            $sql = "CREATE TABLE $table ($fieldDefinitions)";
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
     */
    public static function use(string $database): bool
    {
        try {
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
     * @param bool $isDropDb
     *
     * @return bool
     */
    public static function drop(string $entity, bool $isDropDb = true): bool
    {
        if ($isDropDb) {
            return self::dropDatabase($entity);
        } else {
            return self::dropTable($entity);
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
     */
    private static function truncate(string $table): bool
    {
        try {
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
     */
    public static function insert(string $table, array $options = []): bool
    {
        try {
            $bindValues = [];
            $keys = array_keys($options);
            $values = array_values($options);
            foreach ($keys as $key) {
                $bindValues[] = ':' . $key;
            }
            $keysString = implode(', ', $keys);
            $bindValuesString = implode(', ', $bindValues);
            $params = array_combine($bindValues, $values);
            $sql = "INSERT INTO $table ($keysString) VALUES ($bindValuesString)";

            return (bool)self::executeQuery($sql, $params);
        } catch (PDOException $e) {
            error_log('Error in Db Insert Method: ' . $e->getMessage());
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
     */
    public static function rename(string $oldTable, string $newTable): bool
    {
        try {
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
    public static function select(string $table, array $options = []): ?array
    {
        try {
            $columnList = $options['columns'] ?? '*';
            $where = $options['where'] ?? null;
            $params = $options['params'] ?? [];
            $fetchAll = $options['fetchAll'] ?? true;
            $sql = "SELECT $columnList FROM $table";
            if ($where) {
                $sql .= " WHERE $where";
            }

            return self::executeQuery($sql, $params, $fetchAll);
        } catch (PDOException $e) {
            error_log('Error in Db Select Method: ' . $e->getMessage());
            return false;
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