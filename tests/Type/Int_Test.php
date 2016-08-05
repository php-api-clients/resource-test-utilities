<?php declare(strict_types=1);

namespace ApiClients\Tests\Tools\ResourceTestUtilities\Type;

use ApiClients\Tools\ResourceTestUtilities\Type\Int_;

class Int_Test extends AbstractTypeTest
{
    public function getType(): string
    {
        return 'int';
    }

    public function getClass(): string
    {
        return Int_::class;
    }
}
