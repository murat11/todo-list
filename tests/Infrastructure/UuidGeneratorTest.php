<?php

namespace Test\Infrastructure;

use App\Infrastructure\Repository\IdGenerator\UuidGenerator;
use PHPUnit\Framework\TestCase;

class UuidGeneratorTest extends TestCase
{
    public function testIdGeneratedOk()
    {
        $id = (new UuidGenerator())->generateId();
        $this->assertNotEmpty($id);
        $this->assertRegExp('/\w{8}(-\w{4}){3}-\w{12}/', $id);
    }
}
