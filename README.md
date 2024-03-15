# Simple-Active-Record

Uses PDO. Support multiple databases: MSSQL, SQLite, PostgreSQL, MySQL and etc.

Full list supported drivers view here:
https://www.php.net/manual/ru/pdo.drivers.php

Simple use: 

use \Src\Database\Connection;
use \Src\Database\Db;

require_once __DIR__ . '/vendor/autoload.php';

$options = [
    'where' => 'id = 4',
    'params' => [
        'email' => 'test@test.ru',
        'login' => 'Ivan Ivanov'
    ]
];
$res = Db::update("t_users", $options);
