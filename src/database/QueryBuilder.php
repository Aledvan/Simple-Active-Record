<?php
declare(strict_types=1);

namespace Src\Database;

class QueryBuilder
{
    /**
     * Prepare data for create table query
     *
     * @param string $table
     * @param array $options
     *
     * @return string
     */
    public static function prepareDataForCreateTableQuery(string $table, array $options): string
    {
        $fieldDefinitions = '';
        foreach ($options as $fieldName => $fieldType) {
            $fieldDefinitions .= "$fieldName $fieldType, ";
        }
        $fieldDefinitions = rtrim($fieldDefinitions, ', ');
        $sql = "CREATE TABLE $table ($fieldDefinitions)";

        return $sql;
    }

    /**
     * Prepare data for create table query
     *
     * @param string $table
     * @param array $options
     *
     * @return array
     */
    public static function prepareDataForInsertQuery(string $table, array $options): array
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

        return [
            'params' => $params,
            'sql' => $sql
        ];
    }

    /**
     * Prepare data for select query
     *
     * @param string $table
     * @param array $options
     *
     * @return array
     */
    public static function prepareDataForSelectQuery(string $table, array $options): array
    {
        $columnList = $options['columns'] ?? '*';
        $where = $options['where'] ?? null;
        $groupBy = $options['groupBy'] ?? null;
        $having = $options['having'] ?? null;
        $orderBy = $options['orderBy'] ?? null;
        $limit = $options['limit'] ?? null;
        $distinct = $options['distinct'] ?? false;
        $distinctKeyword = $distinct ? 'DISTINCT' : '';
        $sql = "SELECT $distinctKeyword $columnList FROM $table";
        $params = $options['params'] ?? [];
        $fetchAll = $options['fetchAll'] ?? true;
        if ($where) {
            $sql .= " WHERE $where";
        }
        if ($orderBy) {
            $sql .= " ORDER BY $orderBy";
        }
        if ($limit) {
            $sql .= " LIMIT $limit";
        }
        if ($groupBy) {
            $sql .= " GROUP BY $groupBy";
            if ($having) {
                $sql .= " HAVING $having";
            }
        }

        return [
            'params' => $params,
            'fetchAll' => $fetchAll,
            'sql' => $sql
        ];
    }

    /**
     * Prepare data for select query
     *
     * @param string $table
     * @param array $options
     *
     * @return array
     */
    public static function prepareDataForDeleteQuery(string $table, array $options): array
    {
        $where = $options['where'] ?? null;
        $params = $options['params'] ?? [];
        $sql = "DELETE FROM $table";
        if ($where) {
            $sql .= " WHERE $where";
        }

        return [
            'params' => $params,
            'sql' => $sql
        ];
    }

    public static function prepareDataForUpdateQuery(string $table, array $options): array
    {
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

        return [
            'sql' => $sql,
            'params' => $params
        ];
    }
}

