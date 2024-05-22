use \Src\Database\Connection;
use \Src\Database\Db;

require_once DIR . '/vendor/autoload.php';

# Simple-Active-Record

Uses PDO. Support multiple databases: MSSQL, SQLite, PostgreSQL, MySQL and etc.

Full list supported drivers view here:
https://www.php.net/manual/ru/pdo.drivers.php

\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//
EXAMPLES FOR USING
\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//

use \Src\Database\Connection;
use \Src\Database\Db;

require_once __DIR__ . '/vendor/autoload.php';

## Creating Database
$options = [
    'isCreateDb' => true
];
$result = Db::create('Database_Name', $options);

## Creating Table
$options = [
    'id' => 'AUTO-INCREMENT PRIMARY KEY',
    'name' => 'VARCHAR(50)',
    'surname' => 'VARCHAR(100)',
    'age' => 'INT'
];
$result = Db::create('Table_Name', $options);

## Dropping Database
$options = [
    'isDropDb' => true
];
$result = Db::drop('Database_Name', $options);

## Dropping Table
$options = [
    'isDropDb' => false
];
$result = Db::drop('Table_Name', $options);

## Use Database
$result = Db::use('Database_Name');

## Truncate Table
$result = Db::truncate('Table_Name');

