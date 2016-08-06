<?php declare(strict_types=1);

namespace ApiClients\Tools\ResourceTestUtilities\Type;

use ApiClients\Tools\ResourceTestUtilities\Type;
use Generator;

class IntType extends AbstractType implements Type
{
    const SCALAR = 'int';

    /**
     * Generate random data
     *
     * @param int $count Amount of rows to generate and return
     * @return Generator
     */
    public function generate(int $count = 100): Generator
    {
        for ($i = 0; $i < $count; $i++) {
            yield mt_rand($i, $count * mt_rand($i, $count));
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
        yield BoolType::class;
        yield FloatType::class;
        yield StringType::class;
        yield DoubleType::class;
    }
}
