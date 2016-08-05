<?php declare(strict_types=1);

namespace ApiClients\Tools\ResourceTestUtilities;

use Generator;

interface Type
{
    /**
     * Generate random data
     *
     * @param int $count Amount of rows to generate and return
     * @return Generator
     */
    public function generate(int $count = 100): Generator;

    /**
     * List of types (FQCN) that are compatible with this type
     *
     * @return Generator
     */
    public function compatible(): Generator;

    /**
     * List of types (FQCN) that are incompatible with this type
     *
     * @return Generator
     */
    public function incompatible(): Generator;
}
