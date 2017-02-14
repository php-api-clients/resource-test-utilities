<?php declare(strict_types=1);

namespace ApiClients\Tests\Tools\ResourceTestUtilities\Type;

use ApiClients\Tools\ResourceTestUtilities\TestCase;

final class FaulthyType_Test extends TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function testConstruct()
    {
        new FaulthyType_();
    }
}
