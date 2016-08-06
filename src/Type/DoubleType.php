<?php declare(strict_types=1);

namespace ApiClients\Tools\ResourceTestUtilities\Type;

use ApiClients\Tools\ResourceTestUtilities\Type;

class DoubleType extends FloatType implements Type
{
    const SCALAR = 'double';
}
