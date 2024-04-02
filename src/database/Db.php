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
        try {
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
        } catch (Exception $e) {
            DbException::setError($e);
            return false;
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
        $sqlData = "CREATE DATABASE $database";
        return (bool)self::executeQuery($sqlData);
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
        $sqlData = QueryBuilder::prepareDataForCreateTableQuery($table, $options);
        return (bool)self::executeQuery($sqlData);
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
        } catch (Exception $e) {
            DbException::setError($e);
        }

        $sqlData = "USE $database";
        return (bool)self::executeQuery($sqlData);
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
        try {
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
        } catch (Exception $e) {
            DbException::setError($e);
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
    private static function dropDatabase(string $database): bool
    {
        $sqlData = "DROP DATABASE $database";
        return (bool)self::executeQuery($sqlData);
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
        $sqlData = "DROP TABLE $table";
        return (bool)self::executeQuery($sqlData);
    }

    /**
     * Truncate table
     *
     * @param string $table
     *
     * @return bool
     * @throws Exception
     */
    public static function truncate(string $table): bool
    {
        try {
            if (empty($table)) {
                throw new Exception('Empty table name');
            }
        } catch (Exception $e) {
            DbException::setError($e);
        }

        $sqlData = "TRUNCATE TABLE $table";
        return (bool)self::executeQuery($sqlData);
    }

    /**
     * Insert new lines and values in table
     *
     * @param string $table
     * @param array $options
     *
     * @return bool
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
                $sqlData = QueryBuilder::prepareDataForInsertQuery($table, $options);
                return (bool)self::executeQuery($sqlData['sql'], $sqlData['params']);
            } else {
                throw new Exception('Invalid options format. Expecting an associative array');
            }
        } catch (Exception $e) {
            DbException::setError($e);
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
        } catch (Exception $e) {
            DbException::setError($e);
        }

        $sqlData = "RENAME TABLE $oldTable TO $newTable";
        return (bool)self::executeQuery($sqlData);
    }

    /**
     * Select values from table
     *
     * @param string $table
     * @param array $options
     *
     * @return ?array
     * @throws Exception
     */
    public static function select(string $table, array $options): ?array
    {
        try {
            if (empty($table) || empty($options)) {
                throw new Exception('Empty table name or options');
            }
        } catch (Exception $e) {
            DbException::setError($e);
        }

        $sqlData = QueryBuilder::prepareDataForSelectQuery($table, $options);
        return self::executeQuery($sqlData['sql'], $sqlData['params'], $sqlData['fetchAll']);
    }

    /**
     * Update values in table
     *
     * @param string $table
     * @param array $options
     *
     * @return bool
     * @throws Exception
     */
    public static function update(string $table, array $options): bool
    {
        try {
            if (empty($table) || empty($options)) {
                throw new Exception('Empty table name or options');
            }
        } catch (Exception $e) {
            DbException::setError($e);
        }

        $sqlData = QueryBuilder::prepareDataForUpdateQuery($table, $options);
        return (bool)self::executeQuery($sqlData['sql'], $sqlData['params']);
    }

    /**
     * Delete lines from table
     *
     * @param string $table
     * @param array $options
     *
     * @return bool
     * @throws Exception
     */
    public static function delete(string $table, array $options): bool
    {
        try {
            if (empty($table) || empty($options)) {
                throw new Exception('Empty table name or options');
            }
        } catch (Exception $e) {
            DbException::setError($e);
        }

        $sqlData = QueryBuilder::prepareDataForDeleteQuery($table, $options);
        return (bool)self::executeQuery($sqlData['sql'], $sqlData['params']);
    }
}