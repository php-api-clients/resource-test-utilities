<?php declare(strict_types=1);

namespace ApiClients\Tools\ResourceTestUtilities;

use Exception;
use Generator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;

class Types
{
    protected static $types = [];

    /**
     * @var bool
     */
    protected static $doneScanning = false;

    /**
     * @return Generator<Type>
     */
    public static function types(): Generator
    {
        if (self::$doneScanning && count(self::$types) > 0) {
            yield from self::$types;
            return;
        }

        $path = __DIR__ . DIRECTORY_SEPARATOR . 'Type' . DIRECTORY_SEPARATOR;
        $directory = new RecursiveDirectoryIterator($path);
        $directory = new RecursiveIteratorIterator($directory);
        foreach ($directory as $node) {
            $nodePath = $node->getPath() . DIRECTORY_SEPARATOR . $node->getFilename();
            if (!is_file($nodePath)) {
                continue;
            }

            $fileName = str_replace('/', '\\', $nodePath);
            $class = __NAMESPACE__ . '\\Type\\' . substr(substr($fileName, strlen($path)), 0, -4);
            if (class_exists($class) && (new ReflectionClass($class))->implementsInterface(Type::class)) {
                $type = new $class;
                self::$types[$type->scalar()] = $type;
                yield $type;
            }
        }

        self::$doneScanning = true;
    }

    /**
     * @param string $type
     * @return bool
     */
    public static function has(string $type): bool
    {
        self::ensureTypes();

        return isset(self::$types[$type]);
    }

    /**
     * @param string $type
     * @return Type
     * @throws Exception
     */
    public static function get(string $type): Type
    {
        self::ensureTypes();

        if (isset(self::$types[$type])) {
            return self::$types[$type];
        }

        throw new Exception('Type "' . $type . '" not found, use has to check"');
    }

    /**
     * A wee bit hacky, but this ensures that when ever `has` or `get` is called before `types`
     * all types are detected and available for `has` and `get`.
     */
    protected static function ensureTypes()
    {
        if (self::$doneScanning && count(self::$types) > 0) {
            return;
        }

        foreach (self::types() as $t) {
        }
    }

    /**
     * Reset state
     */
    public static function reset()
    {
        self::$types = [];
        self::$doneScanning = false;
    }
}
