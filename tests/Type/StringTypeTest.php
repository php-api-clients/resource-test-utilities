<?php declare(strict_types=1);

namespace ApiClients\Tests\Tools\ResourceTestUtilities\Type;

use ApiClients\Tools\ResourceTestUtilities\Type\StringType;

class StringTypeTest extends AbstractTypeTest
{
    public function getType(): string
    {
        return 'string';
    }

    public function getClass(): string
    {
        return StringType::class;
    }
}
