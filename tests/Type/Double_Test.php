<?php declare(strict_types=1);

namespace ApiClients\Tests\Tools\ResourceTestUtilities\Type;

use ApiClients\Tools\ResourceTestUtilities\Type\Double_;

class Double_Test extends AbstractTypeTest
{
    public function getType(): string
    {
        return 'double';
    }

    public function getClass(): string
    {
        return Double_::class;
    }
}
