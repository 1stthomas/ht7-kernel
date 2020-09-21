<?php

namespace Ht7\Kernel\Tests\Config;

use \InvalidArgumentException;
use \ReflectionClass;
use \Ht7\Kernel\Config\LockList;
use \Ht7\Kernel\Config\LockListType;
use \Ht7\Kernel\Config\ConfigPathTypes;
use \Ht7\Kernel\Config\Storage\ConfigStorageUnit;
use \PHPUnit\Framework\TestCase;

class LockListTest extends TestCase
{

    public function testAdd()
    {
        $className = LockList::class;

        $mockType = $this->getMockBuilder(LockListType::class)
                ->setMethods(['getHash'])
                ->disableOriginalConstructor()
                ->getMock();

        $mockType->expects($this->exactly(3))
                ->method('getHash')
                ->willReturn(ConfigPathTypes::KERNEL);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['has', 'cleanup'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('has')
                ->with($mockType->getHash())
                ->willReturn(false);
        $mock->expects($this->once())
                ->method('cleanup')
                ->with($mockType);

        $reflectedClass = new ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, []);

        $mock->add($mockType);
    }

    public function testAddDuplicate()
    {
        $className = LockList::class;

        $mockType = $this->getMockBuilder(LockListType::class)
                ->setMethods(['getHash'])
                ->disableOriginalConstructor()
                ->getMock();

        $mockType->expects($this->exactly(3))
                ->method('getHash')
                ->willReturn(ConfigPathTypes::KERNEL);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['has'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('has')
                ->with($mockType->getHash())
                ->willReturn(true);

        $reflectedClass = new ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, []);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('/has already been defined/');

        $mock->add($mockType);
    }

    public function testAddWrongInstance()
    {
        $className = LockList::class;

        $mockType = $this->getMockBuilder(ConfigStorageUnit::class)
                ->setMethods(['getHash'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock = $this->getMockBuilder($className)
                ->setMethods(['has'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, []);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('/instance of ' . get_class(LockListType) . '/');

        $mock->add($mockType);
    }

    public function testAddMultiple()
    {
        $className = LockList::class;

        $locks = [
            ConfigPathTypes::KERNEL => [
                'categories.cache.locks.kernel',
                'definitions.test'
            ],
            ConfigPathTypes::APP => [
                'categories.cache.locks.app',
                'definitions.test2'
            ],
            ConfigPathTypes::OVERRIDE => [
                'categories.cache.locks.override',
                'definitions.test3'
            ],
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['add'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->exactly(3))
                ->method('add')
                ->withConsecutive(
                        [new LockListType(ConfigPathTypes::KERNEL, $locks[ConfigPathTypes::KERNEL])],
                        [new LockListType(ConfigPathTypes::APP, $locks[ConfigPathTypes::APP])],
                        [new LockListType(ConfigPathTypes::OVERRIDE, $locks[ConfigPathTypes::OVERRIDE])]
        );

        $mock->addMultiple($locks);
    }

    public function testAddMultipleIndexed()
    {
        $className = LockList::class;

        $locks = [
            'categories.cache.locks.kernel',
            'definitions.test',
            'categories.cache.locks.app',
            'definitions.test2'
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['add'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->expectException(InvalidArgumentException::class);

        $mock->addMultiple($locks);
    }

    public function testCleanup()
    {
        $className = LockList::class;

        $locks = [
            'categories.cache.locks.kernel',
            'definitions.test',
            'categories.cache.locks.app',
            'definitions.test2'
        ];
        $locksNew = [
            'categories.cache.locks.kernel',
            'definitions.test',
            'categories.cache.locks.override',
            'categories.cache.locks.app',
            'definitions.test2',
            'definitions.test3'
        ];

        $mockType = $this->getMockBuilder(LockListType::class)
                ->setMethods(['getAll', 'remove'])
                ->disableOriginalConstructor()
                ->getMock();

        $mockType->expects($this->once())
                ->method('getAll')
                ->willReturn($locksNew);
        $mockType->expects($this->exactly(4))
                ->method('remove')
                ->withConsecutive([0], [1], [3], [4]);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getLocksSequence'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('getLocksSequence')
                ->willReturn($locks);

        $mock->cleanup($mockType);
    }

    public function testGet()
    {
        $className = LockList::class;

        $locks = [
            'categories.cache.locks.kernel',
            'definitions.test',
            'categories.cache.locks.app',
            'definitions.test2'
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getLocksSequence'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new ReflectionClass($className);
        $property = $reflectedClass->getProperty('items');
        $property->setAccessible(true);
        $property->setValue(
                $mock,
                [ConfigPathTypes::KERNEL => (new LockListType(ConfigPathTypes::KERNEL, $locks))]
        );

        $this->assertInstanceOf(LockListType::class, $mock->get(ConfigPathTypes::KERNEL));
    }

    public function testGetLockedByConfigPathType()
    {
        $className = LockList::class;
        $className2 = LockListType::class;

        $mockT1 = $this->getMockBuilder($className2)
                ->setMethods(['has'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockT1->expects($this->exactly(2))
                ->method('has')
                ->withConsecutive(['test1'], ['test2'])
                ->willReturnOnConsecutiveCalls(false, false);

        $mockT2 = $this->getMockBuilder($className2)
                ->setMethods(['has'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockT2->expects($this->exactly(2))
                ->method('has')
                ->withConsecutive(['test1'], ['test2'])
                ->willReturnOnConsecutiveCalls(false, false);

        $mockT3 = $this->getMockBuilder($className2)
                ->setMethods(['has'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockT3->expects($this->exactly(2))
                ->method('has')
                ->withConsecutive(['test1'], ['test2'])
                ->willReturnOnConsecutiveCalls(true, false);

        $mockT4 = $this->getMockBuilder($className2)
                ->setMethods(['has'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockT4->expects($this->any())
                ->method('has')
                ->withConsecutive(['test2'])
                ->willReturnOnConsecutiveCalls(false);

        $locks = [
            ConfigPathTypes::KERNEL => $mockT1,
            ConfigPathTypes::APP => $mockT2,
            ConfigPathTypes::OVERRIDE => $mockT3,
            ConfigPathTypes::CACHE => $mockT4,
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getLocksSequence'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new ReflectionClass($className);
        $property = $reflectedClass->getProperty('items');
        $property->setAccessible(true);
        $property->setValue($mock, $locks);

        $this->assertEquals(ConfigPathTypes::OVERRIDE, $mock->getLockedByConfigPathType('test1'));

        $this->assertFalse($mock->getLockedByConfigPathType('test2'));
    }

    public function testGetLockListByConfigPathTypes()
    {
        $className = LockList::class;

        $cpts = [
            ConfigPathTypes::KERNEL,
            ConfigPathTypes::APP,
            ConfigPathTypes::OVERRIDE
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getLocksByConfigPathTypes'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('getLocksByConfigPathTypes')
                ->with($cpts)
                ->willReturn([]);

        $this->assertInstanceOf(LockList::class, $mock->getLockListByConfigPathTypes($cpts));
    }

    public function testGetLocksByConfigPathTypes()
    {
        $className = LockList::class;
        $className2 = LockListType::class;

        $cpts = [
            ConfigPathTypes::KERNEL,
            ConfigPathTypes::APP
        ];

        $mockT1 = $this->getMockBuilder($className2)
                ->setMethods(['get'])
                ->disableOriginalConstructor()
                ->getMock();

        $mockT2 = $this->getMockBuilder($className2)
                ->setMethods(['get'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock = $this->getMockBuilder($className)
                ->setMethods(['get'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->exactly(2))
                ->method('get')
                ->withConsecutive([ConfigPathTypes::KERNEL], [ConfigPathTypes::APP])
                ->willReturnOnConsecutiveCalls($mockT1, $mockT2);

        $actual = $mock->getLocksByConfigPathTypes($cpts);

        $this->assertIsArray($actual);
        $this->assertCount(2, $actual);

        $this->assertArrayHasKey(ConfigPathTypes::KERNEL, $actual);
        $this->assertArrayHasKey(ConfigPathTypes::APP, $actual);

        $this->assertContains($mockT1, $actual);
        $this->assertContains($mockT2, $actual);
    }

    public function testGetLocksSequence()
    {
        $className = LockList::class;
        $className2 = LockListType::class;

        $expected = [
            'categories.cache.locks.kernel',
            'definitions.test',
            'definitions.test01',
            'categories.cache.locks.app',
            'definitions.test2'
        ];

        $mockT1 = $this->getMockBuilder($className2)
                ->setMethods(['getAll'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockT1->expects($this->once())
                ->method('getAll')
                ->willReturn([
                    'categories.cache.locks.kernel',
                    'definitions.test',
        ]);

        $mockT2 = $this->getMockBuilder($className2)
                ->setMethods(['getAll'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockT2->expects($this->once())
                ->method('getAll')
                ->willReturn([
                    'definitions.test01',
                    'categories.cache.locks.app',
                    'definitions.test2'
        ]);

        $locks = [
            ConfigPathTypes::KERNEL => $mockT1,
            ConfigPathTypes::APP => $mockT2
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getAll'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('getAll')
                ->willReturn($locks);

        $reflectedClass = new ReflectionClass($className);
        $property = $reflectedClass->getProperty('items');
        $property->setAccessible(true);
        $property->setValue($mock, $locks);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, []);

        $actual = $mock->getLocksSequence();

        $this->assertEquals($expected, $actual);
    }

    public function testIsLocked()
    {
        $className = LockList::class;

        $locks = [
            'categories.cache.locks.kernel',
            'definitions.test'
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getLockedByConfigPathType'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->exactly(2))
                ->method('getLockedByConfigPathType')
                ->withConsecutive([$locks[0]], [$locks[1]])
                ->willReturnOnConsecutiveCalls(ConfigPathTypes::KERNEL, false);

        $this->assertTrue($mock->isLocked($locks[0]));
        $this->assertFalse($mock->isLocked($locks[1]));
    }

    public function testIsLockedByConfigPathType()
    {
        $className = LockList::class;
        $className2 = LockListType::class;

        $locks = [
            'categories.cache.locks.kernel',
            'definitions.test',
            'definitions.test3'
        ];
        $cpts = [
            ConfigPathTypes::KERNEL,
            ConfigPathTypes::APP,
            ConfigPathTypes::OVERRIDE,
        ];

        $mockT1 = $this->getMockBuilder($className2)
                ->setMethods(['getAll'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockT1->expects($this->once())
                ->method('getAll')
                ->willReturn([
                    'categories.cache.locks.kernel',
                    'definitions.test',
        ]);

        $mockT2 = $this->getMockBuilder($className2)
                ->setMethods(['getAll'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockT2->expects($this->once())
                ->method('getAll')
                ->willReturn([
                    'categories.cache.locks.app',
                    'definitions.test2',
        ]);

        $mockT3 = $this->getMockBuilder($className2)
                ->setMethods(['getAll'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockT3->expects($this->once())
                ->method('getAll')
                ->willReturn([]);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['get'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->exactly(3))
                ->method('get')
                ->withConsecutive([$cpts[0]], [$cpts[1]], [$cpts[2]])
                ->willReturnOnConsecutiveCalls($mockT1, $mockT2, $mockT3);

        $this->assertTrue($mock->isLockedByConfigPathType($locks[0], $cpts[0]));
        $this->assertFalse($mock->isLockedByConfigPathType($locks[1], $cpts[1]));
        $this->assertFalse($mock->isLockedByConfigPathType($locks[2], $cpts[2]));
    }

    public function testIsLockedByConfigPathTypes()
    {
        $className = LockList::class;

        $locks = [
            'categories.cache.locks.kernel',
            'definitions.test',
            'definitions.test3'
        ];
        $cpts = [
            ConfigPathTypes::KERNEL,
            ConfigPathTypes::APP,
            ConfigPathTypes::OVERRIDE
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getLockedByConfigPathType'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->exactly(3))
                ->method('getLockedByConfigPathType')
                ->withConsecutive([$locks[0]], [$locks[1]], [$locks[2]])
                ->willReturnOnConsecutiveCalls(
                        ConfigPathTypes::KERNEL,
                        ConfigPathTypes::CACHE,
                        ConfigPathTypes::APP
        );

        $this->assertTrue($mock->isLockedByConfigPathTypes($locks[0], $cpts));
        $this->assertFalse($mock->isLockedByConfigPathTypes($locks[1], $cpts));
        $this->assertTrue($mock->isLockedByConfigPathTypes($locks[2], $cpts));
    }

    public function testLoad()
    {
        $className = LockList::class;

        $data = ['categories.cache.locks.kernel'];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['addMultiple'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('addMultiple')
                ->with($data);

        $mock->load($data);
    }

}
