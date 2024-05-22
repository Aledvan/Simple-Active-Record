<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Src\Database\Db;
use Src\Exception\DbException;

class DbTest extends TestCase
{
    public function testCreateDatabase(): void
    {
        $databaseName = 'test_database';
        $options = [
            'isCreateDb' => true
        ];

        $this->assertTrue(Db::create($databaseName, $options));
    }

    public function testCreateTable(): void
    {
        $tableName = 'test_table';
        $options = [
            'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
            'name' => 'VARCHAR(50)',
            'age' => 'INT'
        ];

        $this->assertTrue(Db::create($tableName, $options));
    }

    public function testUseDatabase(): void
    {
        $databaseName = 'test_database';

        $this->assertTrue(Db::use($databaseName));
    }

    public function testDropDatabase(): void
    {
        $databaseName = 'test_database';
        $options = ['isDropDb' => true];

        $this->assertTrue(Db::drop($databaseName, $options));
    }

    public function testDropTable(): void
    {
        $tableName = 'test_table';

        $this->assertTrue(Db::drop($tableName, []));
    }

    public function testTruncateTable(): void
    {
        $tableName = 'test_table';

        $this->assertTrue(Db::truncate($tableName));
    }

    public function testInsert(): void
    {
        $tableName = 'test_table';
        $options = [
            'columns' => [
                'id' => 1,
                'name' => 'test_name',
            ],
        ];

        $this->assertTrue(Db::insert($tableName, $options));
    }

    public function testRenameTable(): void
    {
        $oldTable = 'test_table';
        $newTable = 'new_test_table';

        $this->assertTrue(Db::rename($oldTable, $newTable));
    }

    public function testSelect(): void
    {
        $table = 'test_table';
        $options = [
            'columns' => ['*'],
            'where' => [
                'id' => 1,
            ],
        ];

        $this->assertIsArray(Db::select($table, $options));
    }

    public function testUpdate(): void
    {
        $table = 'test_table';
        $options = [
            'set' => [
                'name' => 'new_test_name',
            ],
            'where' => [
                'id' => 1,
            ],
        ];

        $this->assertTrue(Db::update($table, $options));
    }

    public function testDelete(): void
    {
        $table = 'test_table';
        $options = [
            'where' => [
                'id' => 1,
            ],
        ];

        $this->assertTrue(Db::delete($table, $options));
    }
}