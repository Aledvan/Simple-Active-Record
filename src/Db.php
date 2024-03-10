<?php

/**
 * Methods for simply execute request to DB, with exception handling
 * 
 * Using: $this->db = new \MsAmgbp\Src\Database\Db();
 */

namespace MsAmgbp\Src\Database;

use MsAmgbp\Src\Database\Query;

class Db
{
    private $query;

    public function __construct()
    {
        $this->query = new Query();
    }

    /**
     * Insert new lines and values in table
     * 
     * Example for using: 
     * $arrayForQuery = [$key1 => $value1, $key2 => $value2];
     * $keys = array_keys($arrayForQuery);
     * $values = array_values($arrayForQuery);
     * $this->db->insert('table_name', $keys, $values);
     * 
     * @param string $tableName
     * @param array $keys
     * @param array $values
     * @return bool
     */
    public function insert(string $tableName, array $keys, array $values):bool
    {
        $bindValues = [];
        foreach ($keys as $key) {
            $bindValues[] = ':' . $key;
        }
        $keysString = implode(', ', $keys);
        $bindValuesString = implode(', ', $bindValues);
        $params = array_combine($bindValues, $values);       
        $sql = "INSERT INTO $tableName ($keysString) VALUES ($bindValuesString)";
        return $this->query->execute($sql, $params) ? true : false;
    }

    /**
     * Select values from table
     * 
     * Example for using:
     * $options = ['where' => 'key = :key LIMIT 1 or another term', 'params' => [':key' => $key], 'fetchAll' => false];
     * $this->db->select('table_name', $options);
     * 
     * @param string $tableName
     * @param array $options
     * @return array
     */
    public function select(string $tableName, $options = []):?array 
    {
        $columnList = $options['columns'] ?? '*';
        $where = $options['where'] ?? null;
        $params = $options['params'] ?? [];
        $fetchAll = $options['fetchAll'] ?? true;
        $sql = "SELECT $columnList FROM $tableName";
        if ($where) {
            $sql .= " WHERE $where";
        }
        return $this->query->execute($sql, $params, $fetchAll);            
    }

    /**
     * Update values in table
     * 
     * Example for using: 
     * $this->db->update('table_name', ['column1 = :join_column1', 'column2 = :join_column2'], 'column3 = :join_column3 OR column4 = :join_column4', [':join_column1' => $variable1 or 'value1', ':join_column2' => $variable2 or 'value2', ':join_column3' => $variable3 or 'value3', ':join_column4' => $variable4 or 'value4']);
     * 
     * @param string $tableName
     * @param array $columnValues
     * @param string $where
     * @param array $params
     * @return array
     */
    public function update(string $tableName, array $bindParams, string $where = null, array $whereParams = []):bool
    {
        $setParts = [];
        $params = [];
        foreach ($bindParams as $bindKey => $bindValue) {
            $setKey = ltrim($bindKey, ':');
            $setParts[] = "$setKey = :$setKey";
            $params[":$setKey"] = $bindValue;
        }
        $setString = implode(', ', $setParts);
        $sql = "UPDATE $tableName SET $setString";
        if ($where) {
            $sql .= " WHERE $where";
            $params = array_merge($params, $whereParams);
        }
        return $this->query->execute($sql, $params) ? true : false; 
    }

    /**
     * Delete lines from table
     * 
     * Example for using: 
     * $this->db->delete('table_name', ['where' => 'id = :id','params' => [':id' => 14],]);
     * 
     * @param string $tableName
     * @param array $options
     * @return bool
     */
    public function delete(string $tableName, $options = []):bool
    {
        $where = $options['where'] ?? null;
        $params = $options['params'] ?? [];
        $sql = "DELETE FROM $tableName";
        if ($where) {
            $sql .= " WHERE $where";
        }
        return $this->query->execute($sql, $params) ? true : false;
    }
}