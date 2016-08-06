<?php declare(strict_types=1);

namespace ApiClients\Tests\Tools\ResourceTestUtilities\Type;

use ApiClients\Tools\ResourceTestUtilities\Type\FloatType;

class FloatTypeTest extends AbstractTypeTest
{
    public function getType(): string
    {
        return 'float';
    }

    public function getClass(): string
    {
        return FloatType::class;
    }
}
