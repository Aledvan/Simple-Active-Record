<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Src\Database\Db;
use Src\Exception\DbException;

class DbTest extends TestCase
{
    private $db;

    protected function setUp(): void
    {
        $this->db = new Db();
    }

    public function testCreateDatabase(): void
    {
        $entity = 'test_database';
        $options = ['isCreateDb' => true];

        $this->assertTrue(Db::create($entity, $options));
    }

    public function testCreateTable(): void
    {
        $entity = 'test_table';
        $options = [
            'columns' => [
                'id' => 'int',
                'name' => 'varchar(255)',
            ],
        ];

        $this->assertTrue(Db::create($entity, $options));
    }

    public function testUseDatabase(): void
    {
        $database = 'test_database';

        $this->assertTrue(Db::use($database));
    }

    public function testDropDatabase(): void
    {
        $entity = 'test_database';
        $options = ['isDropDb' => true];

        $this->assertTrue(Db::drop($entity, $options));
    }

    public function testDropTable(): void
    {
        $entity = 'test_table';

        $this->assertTrue(Db::drop($entity, []));
    }

    public function testTruncateTable(): void
    {
        $table = 'test_table';

        $this->assertTrue(Db::truncate($table));
    }

    public function testInsert(): void
    {
        $table = 'test_table';
        $options = [
            'columns' => [
                'id' => 1,
                'name' => 'test_name',
            ],
        ];

        $this->assertTrue(Db::insert($table, $options));
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

        $result = Db::select($table, $options);
        $this->assertIsArray($result);
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