<?php declare(strict_types=1);

namespace ApiClients\Tools\ResourceTestUtilities\Type;

use ApiClients\Tools\ResourceTestUtilities\Type;
use Generator;

class BoolType extends AbstractType implements Type
{
    const SCALAR = 'bool';

    /**
     * Generate random data
     *
     * @param int $count Amount of rows to generate and return
     * @return Generator
     */
    public function generate(int $count = 100): Generator
    {
        for ($i = 0; $i < $count; $i++) {
            yield (bool)mt_rand(0, 1);
        }
    }

    /**
     * List of types that are compatible with this type
     *
     * @return Generator
     */
    public function compatible(): Generator
    {
        yield static::class;
    }

    /**
     * List of types that are incompatible with this type
     *
     * @return Generator
     */
    public function incompatible(): Generator
    {
        yield IntType::class;
    }
}
