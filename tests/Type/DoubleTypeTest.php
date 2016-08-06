<?php declare(strict_types=1);

namespace ApiClients\Tests\Tools\ResourceTestUtilities\Type;

use ApiClients\Tools\ResourceTestUtilities\Type\DoubleType;

class DoubleTypeTest extends AbstractTypeTest
{
    public function getType(): string
    {
        return 'double';
    }

    public function getClass(): string
    {
        return DoubleType::class;
    }
}
