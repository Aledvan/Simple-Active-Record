<?php

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
    }

    /**
     * Select values from table
     * 
     *
     * @param string $table
     * @param array $options
     * @return array|null
     */
    /*public static function select(string $table, array $options = []): array|null
    {
        $columnList = $options['columns'] ?? '*';
        $where = $options['where'] ?? null;
        $params = $options['params'] ?? [];
        $fetchAll = $options['fetchAll'] ?? true;
        $sql = "SELECT $columnList FROM $table";
        if ($where) {
            $sql .= " WHERE $where";
        }
        return $this->query->execute($sql, $params, $fetchAll);            
    }*/

    /**
     * Update values in table
     *
     * @param string $table
     * @param array $options
     * @return bool
     */
    /*public static function update(string $table, array $options = []): bool
    {
        $setParts = [];
        $params = [];
        foreach ($bindParams as $bindKey => $bindValue) {
            $setKey = ltrim($bindKey, ':');
            $setParts[] = "$setKey = :$setKey";
            $params[":$setKey"] = $bindValue;
        }
        $setString = implode(', ', $setParts);
        $sql = "UPDATE $table SET $setString";
        if ($where) {
            $sql .= " WHERE $where";
            $params = array_merge($params, $whereParams);
        }
        return $this->query->execute($sql, $params) ? true : false; 
    }*/

    /**
     * Delete lines from table
     *
     * 
     * @param string $table
     * @param array $options
     * @return bool
     */
    public static function delete(string $table, $options = []): bool
    {
        $where = $options['where'] ?? null;
        $params = $options['params'] ?? [];
        $sql = "DELETE FROM $table";
        if ($where) {
            $sql .= " WHERE $where";
        }
        return (bool)self::executeQuery($sql, $params);
    }
}