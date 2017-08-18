<?php
declare(strict_types=1);

namespace ApiClients\Tools\ResourceTestUtilities;

use ReflectionClass;
use Throwable;
use TypeError;

abstract class AbstractEmptyResourceTest extends TestCase
{
    abstract public function getSyncAsync(): string;

    abstract public function getClass(): string;

    public function provideProperties(): array
    {
        $yield = [];
        $class = new ReflectionClass($this->getClass());

        foreach ($class->getMethods() as $method) {
            $yield[] = [$method->getName()];
        }

        return [[$yield]];
    }

    /**
     * @dataProvider provideProperties
     * @param mixed $args
     */
    public function testProperties($args)
    {
        $class = $this->getClass();
        $resource = new $class();

        foreach ($args as $arg) {
            list($method) = $arg;

            try {
                $resource->{$method}();
            } catch (TypeError $e) {
                self::assertTrue(true);
                continue;
            } catch (Throwable $t) {
                self::fail('Should have thrown a TypeError instead of a ' . get_class($t));
            }
        }
    }
}
