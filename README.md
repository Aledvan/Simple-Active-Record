# Simple-Active-Record

Uses PDO. Support multiple databases: MSSQL, SQLite, PostgreSQL, MySQL and etc.

Full list supported drivers view here:
https://www.php.net/manual/ru/pdo.drivers.php

## EXAMPLES FOR USING

```sh
use \Src\Database\Connection;
use \Src\Database\Db;

require_once __DIR__ . '/vendor/autoload.php';
```

### Creating Database
```php
<?php
$options = [
    'isCreateDb' => true
];
$result = Db::create('Database_Name', $options);

```

### Creating Table
```php
<?php
$options = [
    'id' => 'AUTO-INCREMENT PRIMARY KEY',
    'name' => 'VARCHAR(50)',
    'surname' => 'VARCHAR(100)',
    'age' => 'INT'
];
$result = Db::create('Table_Name', $options);

```

### Dropping Database
```php
<?php
$options = [
    'isDropDb' => true
];
$result = Db::drop('Database_Name', $options);

```

### Dropping Table
```php
<?php
$options = [
    'isDropDb' => false
];
$result = Db::drop('Table_Name', $options);

```

### Use Database
```php
<?php
$result = Db::use('Database_Name');

```

### Truncate Table
```php
<?php
$result = Db::truncate('Table_Name');

```

