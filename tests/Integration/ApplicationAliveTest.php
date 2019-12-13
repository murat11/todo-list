<?php declare(strict_types=1);

namespace Test\Integration;

class ApplicationAliveTest extends ApplicationTestCase
{
    public function testApplicationAlive()
    {
        $this->connection->connect();
        $this->assertTrue($this->connection->isConnected());
    }
}
