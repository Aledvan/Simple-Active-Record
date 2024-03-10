<?php

use Src\Database\Connection;

require_once __DIR__ . '/autoload.php';

$dbh = Connection::init();

var_dump($dbh);