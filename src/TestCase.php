<?php
declare(strict_types=1);

namespace ApiClients\Tools\ResourceTestUtilities;

use ApiClients\Foundation\Hydrator\Factory;
use ApiClients\Foundation\Hydrator\Options;
use ApiClients\Tools\CommandBus\CommandBus;
use ApiClients\Tools\TestUtilities\TestCase as BaseTestCase;
use DI\ContainerBuilder;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use React\EventLoop\Factory as LoopFactory;
use React\EventLoop\LoopInterface;

abstract class TestCase extends BaseTestCase
{
    const DEFAULT_GENERATED_CLASS_NAMESPACE = 'GHGC_%s';

    /**
     * @var string
     */
    private $tmpDir;

    /**
     * @var string
     */
    private $tmpNamespace;

    public function setUp()
    {
        parent::setUp();
        $crc32 = crc32(get_class($this));
        $this->tmpDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('act-' . $crc32, true) . DIRECTORY_SEPARATOR;
        mkdir($this->tmpDir, 0777, true);
        $this->tmpNamespace = sprintf(
            static::DEFAULT_GENERATED_CLASS_NAMESPACE,
            crc32(uniqid((string)$crc32, true))
        );
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->rmdir($this->tmpDir);
    }

    protected function rmdir($dir)
    {
        $directory = dir($dir);
        while (false !== ($entry = $directory->read())) {
            if (in_array($entry, ['.', '..'])) {
                continue;
            }

            if (is_dir($dir . $entry)) {
                $this->rmdir($dir . $entry . DIRECTORY_SEPARATOR);
                continue;
            }

            if (is_file($dir . $entry)) {
                unlink($dir . $entry);
                continue;
            }
        }
        $directory->close();
        rmdir($dir);
    }

    protected function getTmpDir(): string
    {
        return $this->tmpDir;
    }

    protected function getRandomNameSpace(): string
    {
        return $this->tmpNamespace;
    }

    public function hydrate($class, $json, $namespace)
    {
        $loop = LoopFactory::create();
        $container = ContainerBuilder::buildDevContainer();
        $container->set(CommandBus::class, $this->createCommandBus($loop));
        return Factory::create($container, [
            Options::NAMESPACE => '',
            Options::NAMESPACE_SUFFIX => $namespace,
            Options::RESOURCE_CACHE_DIR => $this->getTmpDir(),
            Options::RESOURCE_NAMESPACE => $this->getRandomNameSpace(),
        ])->hydrateFQCN($class, $json);
    }

    protected function createCommandBus(LoopInterface $loop, array $map = []): CommandBus
    {
        $commandHandlerMiddleware = new CommandHandlerMiddleware(
            new ClassNameExtractor(),
            new InMemoryLocator($map),
            new HandleInflector()
        );

        return new CommandBus(
            $loop,
            $commandHandlerMiddleware
        );
    }
}
