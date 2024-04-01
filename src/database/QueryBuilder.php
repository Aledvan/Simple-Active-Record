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
     * @return string
     */
    public static function prepareDataForSelectQuery(string $table, array $options): string
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

        return $sql;
    }
}

