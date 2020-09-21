<?php

namespace Ht7\Kernel\Tests\Config;

use \ReflectionClass;
use \RuntimeException;
use \Ht7\Kernel\Config\ConfigLoadingSequence;
use \Ht7\Kernel\Config\ConfigPathTypes;
use \PHPUnit\Framework\TestCase;

class ConfigLoadingSequenceTest extends TestCase
{

    public function testContruct()
    {
        $className = ConfigLoadingSequence::class;

        $default = [
            ConfigPathTypes::KERNEL,
            ConfigPathTypes::APP
        ];
        $additional = [
            ConfigPathTypes::OVERRIDE,
            ConfigPathTypes::CACHE
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setByIndex'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->exactly(2))
                ->method('setByIndex')
                ->withConsecutive(
                        [$this->equalTo(ConfigLoadingSequence::DEFAULTS), $this->equalTo($default)],
                        [$this->equalTo(ConfigLoadingSequence::ADDITIONAL), $this->equalTo($additional)]
        );

        $reflectedClass = new ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, $default, $additional);
    }

    public function testContructWithEmptyAdditional()
    {
        $className = ConfigLoadingSequence::class;

        $default = [
            ConfigPathTypes::KERNEL,
            ConfigPathTypes::APP
        ];
        $additional = [];
        $expected = [
            ConfigLoadingSequence::ADDITIONAL => []
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setByIndex'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('setByIndex')
                ->with(
                        $this->equalTo(ConfigLoadingSequence::DEFAULTS),
                        $this->equalTo($default)
        );

        $reflectedClass = new ReflectionClass($className);
        $property = $reflectedClass->getProperty('sequence');
        $property->setAccessible(true);
        $property->setValue($mock, null);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, $default, $additional);

        $this->assertEquals($expected, $property->getValue($mock));
    }

    public function testGet()
    {
        $className = ConfigLoadingSequence::class;

        $sequence = [
            ConfigLoadingSequence::DEFAULTS => [
                ConfigPathTypes::KERNEL,
                ConfigPathTypes::APP
            ],
            ConfigLoadingSequence::ADDITIONAL => [],
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setByIndex'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new ReflectionClass($className);
        $property = $reflectedClass->getProperty('sequence');
        $property->setAccessible(true);
        $property->setValue($mock, $sequence);

        $this->assertEquals($sequence[ConfigLoadingSequence::DEFAULTS], $mock->get(ConfigLoadingSequence::DEFAULTS));
        $this->assertEquals(null, $mock->get('unkonwn_index'));
    }

    public function testGetCategories()
    {
        $className = ConfigLoadingSequence::class;

        $expected = [
            ConfigLoadingSequence::DEFAULTS,
            ConfigLoadingSequence::ADDITIONAL,
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setByIndex'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->assertEquals($expected, $mock->getCategories());
    }

    public function testGetSequence()
    {
        $className = ConfigLoadingSequence::class;

        $sequence = [
            ConfigLoadingSequence::DEFAULTS => [
                ConfigPathTypes::KERNEL => 'kernelC',
                ConfigPathTypes::APP => 'appC'
            ],
            ConfigLoadingSequence::ADDITIONAL => [
                ConfigPathTypes::CACHE => 'cacheC',
                ConfigPathTypes::OVERRIDE => 'overrideC'
            ],
        ];

        $expected = [
            ConfigPathTypes::KERNEL,
            ConfigPathTypes::APP,
            ConfigPathTypes::CACHE,
            ConfigPathTypes::OVERRIDE
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setByIndex'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new ReflectionClass($className);
        $property = $reflectedClass->getProperty('sequence');
        $property->setAccessible(true);
        $property->setValue($mock, $sequence);

        $this->assertEquals($expected, $mock->getSequence());
    }

    public function testGetSequenceTo()
    {
        $className = ConfigLoadingSequence::class;

        $sequence = [
            ConfigPathTypes::KERNEL,
            ConfigPathTypes::APP,
            ConfigPathTypes::CACHE,
            ConfigPathTypes::OVERRIDE
        ];

        $expected = [
            ConfigPathTypes::KERNEL,
            ConfigPathTypes::APP,
            ConfigPathTypes::CACHE,
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getSequence'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('getSequence')
                ->willReturn($sequence);

        $this->assertEquals($expected, $mock->getSequenceTo(ConfigPathTypes::CACHE));
    }

    public function testSetByIndex()
    {
        $className = ConfigLoadingSequence::class;

        $sequence = [
            ConfigLoadingSequence::DEFAULTS => [
                'kernel' => 'kernel',
                'app' => 'app'
            ]
        ];
        $expected = [
            ConfigLoadingSequence::DEFAULTS => [
                'kernel' => 'kernel',
                'app' => 'app'
            ],
            ConfigLoadingSequence::ADDITIONAL => [
                'cache' => 'cache',
                'override' => 'override'
            ]
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getSequence'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new ReflectionClass($className);
        $property = $reflectedClass->getProperty('sequence');
        $property->setAccessible(true);
        $property->setValue($mock, $sequence);

        $mock->setByIndex(ConfigLoadingSequence::ADDITIONAL, ['cache' => 'cache', 'override' => 'override']);

        $this->assertEquals($expected, $property->getValue($mock));
    }

    public function testSetByIndexWithDuplicates()
    {
        $className = ConfigLoadingSequence::class;

        $sequence = [
            ConfigLoadingSequence::DEFAULTS => [
                'kernel',
                'app'
            ]
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getSequence'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new ReflectionClass($className);
        $property = $reflectedClass->getProperty('sequence');
        $property->setAccessible(true);
        $property->setValue($mock, $sequence);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('/duplicated config path types/');

        $mock->setByIndex(ConfigLoadingSequence::ADDITIONAL, ['cache', 'kernel']);
    }

    public function testSetByIndexWithNotEmptyDefaults()
    {
        $className = ConfigLoadingSequence::class;

        $sequence = [
            ConfigLoadingSequence::DEFAULTS => [
                'kernel',
                'app'
            ]
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getSequence'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new ReflectionClass($className);
        $property = $reflectedClass->getProperty('sequence');
        $property->setAccessible(true);
        $property->setValue($mock, $sequence);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('/defaults have already been set/');

        $mock->setByIndex(ConfigLoadingSequence::DEFAULTS, []);
    }

    public function testSetByIndexWithUnkownCategory()
    {
        $className = ConfigLoadingSequence::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getSequence'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('/Undefined sequence category/');

        $mock->setByIndex('unkown_index', []);
    }

}
