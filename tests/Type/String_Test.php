<?php declare(strict_types=1);

namespace ApiClients\Tests\Tools\ResourceTestUtilities\Type;

use ApiClients\Tools\ResourceTestUtilities\Type\String_;

class String_Test extends AbstractTypeTest
{
    public function getType(): string
    {
        return 'string';
    }

    public function getClass(): string
    {
        return String_::class;
    }
}
