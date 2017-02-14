<?php declare(strict_types=1);

namespace ApiClients\Tests\Tools\ResourceTestUtilities\Type;

use ApiClients\Tools\ResourceTestUtilities\Type\BoolType;

final class BoolTypeTest extends AbstractTypeTest
{
    public function getType(): string
    {
        return 'bool';
    }

    public function getClass(): string
    {
        return BoolType::class;
    }
}
