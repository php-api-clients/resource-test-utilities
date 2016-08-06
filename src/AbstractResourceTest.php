<?php
declare(strict_types=1);

namespace ApiClients\Tools\ResourceTestUtilities;

use ApiClients\Foundation\Resource\ResourceInterface;
use Doctrine\Common\Inflector\Inflector;
use Generator;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlockFactory;
use ReflectionClass;
use ReflectionProperty;
use TypeError;

abstract class AbstractResourceTest extends TestCase
{
    abstract public function getClass(): string;
    abstract public function getNamespace(): string;

    public function provideProperties(): Generator
    {
        yield from $this->providePropertiesGenerator('compatible');
    }

    public function providePropertiesIncompatible(): Generator
    {
        yield from $this->providePropertiesGenerator('incompatible');
    }

    public function providePropertiesGenerator(string $typeMethod): Generator
    {
        $class = new ReflectionClass($this->getClass());

        $jsonTemplate = [];
        foreach ($class->getProperties() as $property) {
            $jsonTemplate[$property->getName()] = '';
        }

        foreach ($class->getProperties() as $property) {
            $method = Inflector::camelize($property->getName());
            $docBlock = $this->getDocBlock($property->getDocComment());

            $varTag = $docBlock->getTagsByName('var');
            if (count($varTag) !== 1) {
                continue;
            }

            $varTag = $varTag[0];
            $scalar = (string)$varTag->getType();
            if ($scalar === '') {
                continue;
            }

            if (!Types::has($scalar)) {
                continue;
            }

            $type = Types::get($scalar);
            yield from $this->generateTypeValues($type, $property, $method, $typeMethod, $jsonTemplate);
        }
    }

    protected function generateTypeValues(
        Type $type,
        ReflectionProperty $property,
        string $method,
        string $typeMethod,
        array $jsonTemplate
    ): Generator {
        $json = $jsonTemplate;
        foreach ($type->$typeMethod() as $typeClass) {
            $methodType = Types::get(constant($typeClass . '::SCALAR'));
            foreach ($methodType->generate(250) as $value) {
                $json[$property->getName()] = $value;
                yield [
                    $property->getName(), // Name of the property to assign data to
                    $method,              // Method to call verifying that data
                    $type,                // The different types of data assiciated with this field
                    $json,                // JSON to use during testing
                    $value,               // Value to check against
                ];
            }
        }
    }

    /**
     * @param $docBlockContents
     * @return DocBlock
     */
    protected function getDocBlock(string $docBlockContents): DocBlock
    {
        if (class_exists('phpDocumentor\Reflection\DocBlockFactory')) {
            return DocBlockFactory::createInstance()->create($docBlockContents);
        }

        return new DocBlock($docBlockContents);
    }

    /**
     * @dataProvider provideProperties
     */
    public function testProperties(string $property, string $method, Type $type, array $json, $value)
    {
        $class = $this->getClass();
        $resource = $this->hydrate(
            str_replace(
                $this->getNamespace(),
                $this->getNamespace() . '\\Async',
                $class
            ),
            $json,
            'Async'
        );
        $this->assertSame($value, $resource->{$method}());
        $this->assertInternalType($type->scalar(), $resource->{$method}());
    }

    /**
     * @dataProvider providePropertiesIncompatible
     * @expectedException TypeError
     */
    public function testPropertiesIncompatible(string $property, string $method, Type $type, array $json, $value)
    {
        $class = $this->getClass();
        $resource = $this->hydrate(
            str_replace(
                $this->getNamespace(),
                $this->getNamespace() . '\\Async',
                $class
            ),
            $json,
            'Async'
        );
        $this->assertSame($value, $resource->{$method}());
    }

    public function testInterface()
    {
        $this->assertTrue(
            is_subclass_of(
                $this->getClass(),
                ResourceInterface::class
            )
        );
    }
}
