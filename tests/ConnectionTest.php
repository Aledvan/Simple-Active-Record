<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Src\Database\Connection;

class ConnectionTest extends TestCase
{
    public function testGetInstanceReturnsPDOInstance()
    {
        $pdoInstance = Connection::getInstance();
        $this->assertInstanceOf(PDO::class, $pdoInstance);
    }

    public function testCloseMethodResetsInstanceToNull()
    {
        Connection::close();
        $reflection = new ReflectionClass(Connection::class);
        $instanceProperty = $reflection->getProperty('instance');
        $instanceProperty->setAccessible(true);
        $instanceValue = $instanceProperty->getValue();

        $this->assertNull($instanceValue);
    }
}
