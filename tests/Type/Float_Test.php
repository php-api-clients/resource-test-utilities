<?php declare(strict_types=1);

namespace ApiClients\Tests\Tools\ResourceTestUtilities\Type;

use ApiClients\Tools\ResourceTestUtilities\Type\Float_;

class Float_Test extends AbstractTypeTest
{
    public function getType(): string
    {
        return 'float';
    }

    public function getClass(): string
    {
        return Float_::class;
    }
}
