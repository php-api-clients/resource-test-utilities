<?php declare(strict_types=1);

namespace ApiClients\Tools\ResourceTestUtilities\Type;

use ApiClients\Tools\ResourceTestUtilities\Type;

abstract class AbstractType
{
    final public function __construct()
    {
        if (!defined('static::SCALAR')) {
            throw new \InvalidArgumentException('Missing SCALAR constant on ' . get_class($this));
        }
    }

    /**
     * Scalar name the type represents.
     *
     * @return string
     */
    public function scalar(): string
    {
        return static::SCALAR;
    }
}
