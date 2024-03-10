<?php

use \Src\Database\Connection;
use \Src\Database\Db;

require_once __DIR__ . '/vendor/autoload.php';

try {
    $options = [
        'login' => 'Aleksey',
        'email' => 'del@del.ru',
        'pass' => '123',
        'date' => date('Y-m-d H:i:s'),
        'name' => 'Aleksey',
        'surname' => 'Kalganov',
        'phone' => '79284546545',
    ];
    Db::insert('t_users', $options);
} catch (\PDOException $e) {
    echo 'Ошибка: ' . $e->getMessage();
}