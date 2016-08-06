<?php declare(strict_types=1);

namespace ApiClients\Tests\Tools\ResourceTestUtilities\Type;

use ApiClients\Tools\ResourceTestUtilities\Type\Bool_;

class Bool_Test extends AbstractTypeTest
{
    public function getType(): string
    {
        return 'bool';
    }

    public function getClass(): string
    {
        return Bool_::class;
    }
}
