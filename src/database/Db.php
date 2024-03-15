<?php
declare(strict_types=1);

namespace Src\Database;

use Src\Database\Query;

class Db extends Query
{
    /**
     * Insert new lines and values in table
     *
     * @param string $table
     * @param array $options
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
        }
    }

    /**
     * Select values from table
     * 
     *
     * @param string $table
     * @param array $options
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
        }
    }

    /**
     * Update values in table
     *
     * @param string $table
     * @param array $options
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
        }
    }

    /**
     * Delete lines from table
     *
     * 
     * @param string $table
     * @param array $options
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
        }
    }
}