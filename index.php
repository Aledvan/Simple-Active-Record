<?php

use \Src\Database\Connection;
use \Src\Database\Db;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
ini_set("error_log", __DIR__ . '/log/errors/php-errors/' . date('Y-m-d') . '.log');
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

$options = [
    'isCreateDb' => true
];
$res = Db::create('db_new_2', $options);
var_dump($res);
