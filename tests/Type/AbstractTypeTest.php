<?php
declare(strict_types=1);

namespace ApiClients\Tests\Tools\ResourceTestUtilities\Type;

abstract class AbstractTypeTest extends \PHPUnit_Framework_TestCase
{
    abstract public function getType(): string;
    abstract public function getClass(): string;

    public function testGenerate()
    {
        $internalType = $this->getType();
        $class = $this->getClass();
        $type = new $class();
        foreach ($type->generate(25000) as $row) {
            $this->assertInternalType($internalType, $row);
        }
    }

    public function provideGenerateCount()
    {
        yield [1];
        yield [13];
        yield [123];
        yield [1000];
    }

    /**
     * @dataProvider provideGenerateCount
     */
    public function testGenerateCount(int $count)
    {
        $class = $this->getClass();
        $type = new $class();
        $rows = 0;
        foreach ($type->generate($count) as $row) {
            $rows++;
        }
        $this->assertSame($count, $rows);
    }

    public function testGenerateDefaultCount()
    {
        $class = $this->getClass();
        $type = new $class();
        $rows = 0;
        foreach ($type->generate() as $row) {
            $rows++;
        }
        $this->assertSame(100, $rows);
    }
}
