<?php
declare(strict_types=1);

namespace Src\Database;

use Src\Database\Query;

class Db extends Query
{
    /**
     * Create new database or table
     *
     * @param string $databaseOrTable
     * @param array $options = []
     *
     * @return bool
     */
    public static function create(string $databaseOrTable, array $options = []): bool
    {
        if (empty($options)) {
            return self::createDatabase($databaseOrTable);
        } else {
            return self::createTable($databaseOrTable, $options);
        }
    }


    private static function createDatabase(string $databaseName): bool
    {
        try {
            $sql = "CREATE DATABASE $databaseName";
            return self::executeQuery($sql);
        } catch (PDOException $e) {
            error_log('Error When Creating Database: ' . $e->getMessage());
            return false;
        }
    }

    private static function createTable(string $tableName, array $fields): bool
    {
        try {
            $fieldDefinitions = '';
            foreach ($fields as $fieldName => $fieldType) {
                $fieldDefinitions .= "$fieldName $fieldType, ";
            }
            $fieldDefinitions = rtrim($fieldDefinitions, ', ');
            $sql = "CREATE TABLE $tableName ($fieldDefinitions)";
            return (bool) self::executeQuery($sql);
        } catch (PDOException $e) {
            error_log('Error When Creating Table: ' . $e->getMessage());
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
            error_log('Error When Using New Database: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Drop database
     *
     * @param string $database
     *
     * @return bool
     */
    public static function drop(string $database): bool
    {
        try {
            $sql = "DROP DATABASE $database";

            return (bool)self::executeQuery($sql);
        } catch (PDOException $e) {
            error_log('Error When Dropping Database: ' . $e->getMessage());
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