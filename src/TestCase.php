<?php
declare(strict_types=1);

namespace ApiClients\Tools\ResourceTestUtilities;

use ApiClients\Foundation\Hydrator\Factory;
use ApiClients\Foundation\Hydrator\Options;
use ApiClients\Tools\CommandBus\CommandBus;
use ApiClients\Tools\CommandBus\CommandBusInterface;
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
    public function hydrate($class, $json, $namespace)
    {
        $loop = LoopFactory::create();
        $commandBus = $this->createCommandBus($loop);
        return Factory::create($loop, $commandBus, [
            Options::NAMESPACE => '',
            Options::NAMESPACE_SUFFIX => $namespace,
            Options::RESOURCE_CACHE_DIR => $this->getTmpDir(),
            Options::RESOURCE_NAMESPACE => $this->getRandomNameSpace(),
        ])->hydrateFQCN($class, $json);
    }

    protected function createCommandBus(LoopInterface $loop, array $map = []): CommandBusInterface
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
