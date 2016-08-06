<?php declare(strict_types=1);

namespace ApiClients\Tests\Tools\ResourceTestUtilities\Type;

use ApiClients\Tools\ResourceTestUtilities\TestCase;
use ApiClients\Tools\ResourceTestUtilities\Type;
use ApiClients\Tools\ResourceTestUtilities\Types;
use Exception;

class TypesTest extends TestCase
{
    public function testTypes()
    {
        $count = 0;
        foreach (Types::types() as $type) {
            $this->assertInstanceOf(Type::class, $type);
            $count++;
        }
        $this->assertTrue($count > 0);
    }

    public function testHas()
    {
        $count = 0;
        foreach (Types::types() as $type) {
            $this->assertTrue(Types::has($type->scalar()));
            $count++;
        }
        $this->assertTrue($count > 0);
    }


    public function testHasnt()
    {
        $this->assertFalse(Types::has('abc'));
    }

    public function testGet()
    {
        $count = 0;
        foreach (Types::types() as $type) {
            $this->assertInstanceOf(Type::class, Types::get($type->scalar()));
            $count++;
        }
        $this->assertTrue($count > 0);
    }

    /**
     * @expectedException Exception
     */
    public function testGetNot()
    {
        Types::reset();
        Types::get('abc');
    }
}
