<?php

use \Src\Database\Connection;
use \Src\Database\Db;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

$options = [
    'id' => 'INT',
    'name' => 'VARCHAR(255)',
];
$res = Db::create('users', $options);
var_dump($res);
