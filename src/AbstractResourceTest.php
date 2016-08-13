<?php
declare(strict_types=1);

namespace ApiClients\Tools\ResourceTestUtilities;

use ApiClients\Foundation\Hydrator\AnnotationInterface;
use ApiClients\Foundation\Hydrator\Annotations\Rename;
use ApiClients\Foundation\Resource\ResourceInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Inflector\Inflector;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlockFactory;
use ReflectionClass;
use ReflectionProperty;
use TypeError;

abstract class AbstractResourceTest extends TestCase
{
    abstract public function getClass(): string;
    abstract public function getNamespace(): string;

    public function provideProperties(): array
    {
        return $this->providePropertiesGenerator('compatible');
    }

    public function providePropertiesIncompatible(): array
    {
        return $this->providePropertiesGenerator('incompatible');
    }

    public function providePropertiesGenerator(string $typeMethod): array
    {
        $yield = [];
        $class = new ReflectionClass($this->getClass());

        $jsonTemplate = [];
        foreach ($class->getProperties() as $property) {
            $key = $property->getName();
            $renamed = self::GetAnnotation($property->getDeclaringClass()->getName(), Rename::class);

            if ($renamed instanceof Rename && $renamed->has($key)) {
                $key = $renamed->get($key);
            }

            $jsonTemplate[$key] = '';
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
            $yield += $this->generateTypeValues($type, $property, $method, $typeMethod, $jsonTemplate);
        }

        return $yield;
    }

    protected function generateTypeValues(
        Type $type,
        ReflectionProperty $property,
        string $method,
        string $typeMethod,
        array $jsonTemplate
    ): array {
        $yield = [];
        $json = $jsonTemplate;
        foreach ($type->$typeMethod() as $typeClass) {
            $methodType = Types::get(constant($typeClass . '::SCALAR'));
            foreach ($methodType->generate(1) as $value) {
                $key = $property->getName();

                $renamed = self::GetAnnotation($property->getDeclaringClass()->getName(), Rename::class);
                if ($renamed instanceof Rename && $renamed->has($key)) {
                    $key = $renamed->get($key);
                }

                $json[$key] = $value;

                $yield[] = [
                    $property->getName(), // Name of the property to assign data to
                    $method,              // Method to call verifying that data
                    $type,                // The different types of data associated with this field
                    $json,                // JSON to use during testing
                    $value,               // Value to check against
                ];
            }
        }

        return $yield;
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
     * @param string $class
     * @param string $annotationClass
     * @return null|AnnotationInterface
     */
    protected static function getAnnotation(string $class, string $annotationClass)
    {
        $annotationReader = new AnnotationReader();

        $annotation = $annotationReader
            ->getClassAnnotation(
                new ReflectionClass($class),
                $annotationClass
            )
        ;

        if (get_class($annotation) === $annotationClass) {
            return $annotation;
        }

        $class = get_parent_class($class);

        if ($class === false) {
            return null;
        }

        return $annotationReader
            ->getClassAnnotation(
                new ReflectionClass($class),
                $annotationClass
            )
            ;
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

        if ($value !== $resource->{$method}()) {
            throw new TypeError();
        }

        $this->fail('We should not reach this');
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
