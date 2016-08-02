<?php
declare(strict_types=1);

namespace ApiClients\Foundation\Tests;

use ApiClients\Foundation\Resource\ResourceInterface;

abstract class AbstractResourceTest extends \PHPUnit_Framework_TestCase
{
    abstract function getClass(): string;

    public function provideProperties()
    {
        foreach ((new \ReflectionClass($this->getClass()))->getProperties() as $property) {
            //
        }
    }

    /**
     * @dataProvider provideProperties
     */
    public function testProperties()
    {
        $this->assertTrue(true);
    }

    public function testInterface()
    {
        $this->assertTrue(
            is_subclass_of(
                $this->getClass(),
                ResourceInterface::class
            )
        );
    }
}
