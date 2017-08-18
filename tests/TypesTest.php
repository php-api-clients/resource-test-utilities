<?php declare(strict_types=1);

namespace ApiClients\Tests\Tools\ResourceTestUtilities\Type;

use ApiClients\Tools\ResourceTestUtilities\TestCase;
use ApiClients\Tools\ResourceTestUtilities\Type;
use ApiClients\Tools\ResourceTestUtilities\Types;
use Exception;

final class TypesTest extends TestCase
{
    public function testTypes()
    {
        $count = 0;
        foreach (Types::types() as $type) {
            self::assertInstanceOf(Type::class, $type);
            $count++;
        }
        self::assertTrue($count > 0);
    }

    public function testHas()
    {
        $count = 0;
        foreach (Types::types() as $type) {
            self::assertTrue(Types::has($type->scalar()));
            $count++;
        }
        self::assertTrue($count > 0);
    }

    public function testHasnt()
    {
        self::assertFalse(Types::has('abc'));
    }

    public function testGet()
    {
        $count = 0;
        foreach (Types::types() as $type) {
            self::assertInstanceOf(Type::class, Types::get($type->scalar()));
            $count++;
        }
        self::assertTrue($count > 0);
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
