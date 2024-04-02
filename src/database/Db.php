<?php
declare(strict_types=1);

namespace Src\Database;

use Exception;
use PDOException;
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
        return self::executeQuery($sql);
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
        $sql = QueryBuilder::prepareDataForCreateTableQuery($table, $options);
        return self::executeQuery($sql);
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
        if (empty($database)) {
            throw new Exception('Empty database name');
        }

        $sql = "USE $database";
        return (bool)self::executeQuery($sql);
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
        $sql = "DROP DATABASE $database";
        return self::executeQuery($sql);
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
        $sql = "DROP TABLE $table";
        return self::executeQuery($sql);
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
        if (empty($database)) {
            throw new Exception('Empty table name');
        }

        $sql = "TRUNCATE TABLE $table";
        return self::executeQuery($sql);
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
        if (empty($oldTable) || empty($newTable)) {
            throw new Exception('Empty old or new table');
        }

        $sql = "RENAME TABLE $oldTable TO $newTable";
        return (bool)self::executeQuery($sql);
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
        if (empty($table) || empty($options)) {
            throw new Exception('Empty table name or options');
        }

        $data = QueryBuilder::prepareDataForSelectQuery($table, $options);
        $sql = $data['sql'];
        $params = $data['params'];
        $fetchAll = $data['fetchAll'];
        return self::executeQuery($sql, $params, $fetchAll);
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
        $data = QueryBuilder::prepareDataForUpdateQuery($table, $options);
        $sql = $data['sql'];
        $params = $data['params'];
        return (bool)self::executeQuery($sql, $params);
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
        $data = QueryBuilder::prepareDataForDeleteQuery($table, $options);
        $sql = $data['sql'];
        $params = $data['params'];
        return (bool)self::executeQuery($sql, $params);
    }
}