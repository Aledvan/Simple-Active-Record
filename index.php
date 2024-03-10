<?php

use \Src\Database\Connection;
use \Src\Database\Db;

require_once __DIR__ . '/vendor/autoload.php';

try {
    $options = [
        'where' => 'id = 4',
        'params' => [
            'email' => 'test@test.ru',
            'login' => 'AntonKarton'
        ]
    ];
    $res = Db::update('t_users', $options);
    var_dump($res);
} catch (\PDOException $e) {
    echo 'Ошибка: ' . $e->getMessage();
}