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
        foreach ($type->generate(100000) as $row) {
            $this->assertInternalType($internalType, $row);
        }
    }
}
