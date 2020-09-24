<?php

namespace Ht7\Kernel\Tests\Config\Storage;

use \InvalidArgumentException;
use \ReflectionClass;
use \Ht7\Kernel\Config\ConfigLoadingSequence;
use \Ht7\Kernel\Config\ConfigPathTypes;
use \Ht7\Kernel\Config\Models\ConfigFileModel;
use \Ht7\Kernel\Config\Models\GenericConfigModel;
use \Ht7\Kernel\Config\Storage\ConfigStorageUnit;
use \Ht7\Kernel\Config\Storage\DummyStorageUnit;
use \Ht7\Kernel\Config\Storage\StorageUnitList;
use \PHPUnit\Framework\TestCase;

class StorageUnitListTest extends TestCase
{

    public function testContruct()
    {
        $className = StorageUnitList::class;

        $sequence = [
            ConfigPathTypes::OVERRIDE,
            ConfigPathTypes::CACHE,
            ConfigPathTypes::APP,
            ConfigPathTypes::KERNEL,
        ];

        $mockSeq = $this->getMockBuilder(ConfigLoadingSequence::class)
                ->setMethods(['getSequence'])
                ->disableOriginalConstructor()
                ->getMock();

        $mockSeq->expects($this->once())
                ->method('getSequence')
                ->willReturn($sequence);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['load'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('load')
                ->with($this->callback(function($subject) {
                            return is_array($subject) && count($subject) === 4;
                        }));

        $reflectedClass = new ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, $mockSeq);
    }

    public function testAdd()
    {
        $className = StorageUnitList::class;
        $className2 = ConfigStorageUnit::class;
        $className3 = DummyStorageUnit::class;

        $mockSu = $this->getMockBuilder($className2)
                ->setMethods(['getHash'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSu->expects($this->exactly(2))
                ->method('getHash')
                ->willReturn(ConfigPathTypes::KERNEL);

        $mockDummy = $this->getMockBuilder($className3)
                ->setMethods(['getHash'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock = $this->getMockBuilder($className)
                ->setMethods(['has'])
                ->disableOriginalConstructor()
                ->getMock();
        $mock->expects($this->once())
                ->method('has')
                ->with($this->equalTo(ConfigPathTypes::KERNEL))
                ->willReturn(true);

        $reflectedClass = new ReflectionClass($className);
        $property = $reflectedClass->getProperty('items');
        $property->setAccessible(true);
        $property->setValue($mock, [ConfigPathTypes::KERNEL => $mockDummy]);

        $mock->add($mockSu);

        $this->assertEquals([ConfigPathTypes::KERNEL => $mockSu], $property->getValue($mock));
    }

    public function testAddDummy()
    {
        $className = StorageUnitList::class;
        $className2 = DummyStorageUnit::class;

        $mockDummy = $this->getMockBuilder($className2)
                ->setMethods(['getHash'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockDummy->expects($this->once())
                ->method('getHash')
                ->willReturn(ConfigPathTypes::KERNEL);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['get'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new ReflectionClass($className);
        $property = $reflectedClass->getProperty('items');
        $property->setAccessible(true);
        $property->setValue($mock, []);

        $mock->add($mockDummy);

        $this->assertEquals([ConfigPathTypes::KERNEL => $mockDummy], $property->getValue($mock));
    }

    public function testAddUnkownConfigPathType()
    {
        $className = StorageUnitList::class;
        $className2 = ConfigStorageUnit::class;

        $mockSu = $this->getMockBuilder($className2)
                ->setMethods(['getHash'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSu->expects($this->exactly(2))
                ->method('getHash')
                ->willReturn(ConfigPathTypes::CACHE);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['has'])
                ->disableOriginalConstructor()
                ->getMock();
        $mock->expects($this->once())
                ->method('has')
                ->with(ConfigPathTypes::CACHE)
                ->willReturn(false);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('/undefined: ' . ConfigPathTypes::CACHE . '/');

        $mock->add($mockSu);
    }

    public function testAddInvalidInstance()
    {
        $className = StorageUnitList::class;
        $className2 = \stdClass::class;

        $mockSu = $this->getMockBuilder($className2)
                ->disableOriginalConstructor()
                ->getMock();

        $mock = $this->getMockBuilder($className)
                ->setMethods(['has'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('/instance of the ConfigStorageUnit class, found ' . get_class($mockSu) . '/');

        $mock->add($mockSu);
    }

    public function testGet()
    {
        $className = StorageUnitList::class;

        $seq = [
            'test1' => 'test11',
            'test2' => 'test21',
            'test3' => 'test31',
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['has'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new ReflectionClass($className);
        $property = $reflectedClass->getProperty('items');
        $property->setAccessible(true);
        $property->setValue($mock, $seq);

        $this->assertEquals($seq['test2'], $mock->get('test2'));
    }

    public function testGetByConfigPathType()
    {
        $className = StorageUnitList::class;
        $className2 = ConfigStorageUnit::class;
        $className3 = GenericConfigModel::class;

        $index = 'definitions.test';
        $expected = 'final test value';

        $mockModel = $this->getMockBuilder($className3)
                ->setMethods(['has', 'get'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockModel->expects($this->once())
                ->method('has')
                ->with($index)
                ->willReturn(true);
        $mockModel->expects($this->once())
                ->method('get')
                ->with($index)
                ->willReturn($expected);

        $mockSu = $this->getMockBuilder($className2)
                ->setMethods(['getDataModel'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSu->expects($this->exactly(2))
                ->method('getDataModel')
                ->willReturn($mockModel);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['get', 'has'])
                ->disableOriginalConstructor()
                ->getMock();
        $mock->expects($this->exactly(2))
                ->method('get')
                ->with(ConfigPathTypes::APP)
                ->willReturn($mockSu);
        $mock->expects($this->once())
                ->method('has')
                ->with(ConfigPathTypes::APP)
                ->willReturn(true);

        $this->assertEquals($expected, $mock->getByConfigPathType($index, ConfigPathTypes::APP));
    }

    public function testGetByConfigPathTypeReturnDefault()
    {
        $className = StorageUnitList::class;
        $className2 = ConfigStorageUnit::class;
        $className3 = GenericConfigModel::class;

        $index = 'definitions.test';
        $expected = '@not found value@';

        $mockModel = $this->getMockBuilder($className3)
                ->setMethods(['has'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockModel->expects($this->once())
                ->method('has')
                ->with($index)
                ->willReturn(false);

        $mockSu = $this->getMockBuilder($className2)
                ->setMethods(['getDataModel'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSu->expects($this->once())
                ->method('getDataModel')
                ->willReturn($mockModel);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['get', 'has'])
                ->disableOriginalConstructor()
                ->getMock();
        $mock->expects($this->once())
                ->method('get')
                ->with(ConfigPathTypes::APP)
                ->willReturn($mockSu);
        $mock->expects($this->once())
                ->method('has')
                ->with(ConfigPathTypes::APP)
                ->willReturn(true);

        $this->assertEquals($expected, $mock->getByConfigPathType($index, ConfigPathTypes::APP, $expected));

        $mock2 = $this->getMockBuilder($className)
                ->setMethods(['has'])
                ->disableOriginalConstructor()
                ->getMock();
        $mock2->expects($this->once())
                ->method('has')
                ->with(ConfigPathTypes::CACHE)
                ->willReturn(false);

        $this->assertEquals($expected, $mock2->getByConfigPathType($index, ConfigPathTypes::CACHE, $expected));
    }

    public function testGetByConfigPathTypes()
    {
        $className = StorageUnitList::class;
        $className2 = ConfigStorageUnit::class;
        $className3 = GenericConfigModel::class;
        $className4 = DummyStorageUnit::class;
        $className5 = ConfigFileModel::class;

        $index = '';
        $cpts = [
            ConfigPathTypes::APP,
            ConfigPathTypes::OVERRIDE,
            ConfigPathTypes::CACHE
        ];
        $expected = 'expected value';

        $mockDummy = $this->getMockBuilder($className4)
                ->setMethods(['getHash'])
                ->disableOriginalConstructor()
                ->getMock();

        $mockSModel1 = $this->getMockBuilder($className5)
                ->setMethods(['getConfigPathType'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSModel1->expects($this->once())
                ->method('getConfigPathType')
                ->willReturn(ConfigPathTypes::KERNEL);
        $mockSu1 = $this->getMockBuilder($className2)
                ->setMethods(['getStorageModel'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSu1->expects($this->once())
                ->method('getStorageModel')
                ->willReturn($mockSModel1);

        $mockSModel2 = $this->getMockBuilder($className5)
                ->setMethods(['getConfigPathType'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSModel2->expects($this->once())
                ->method('getConfigPathType')
                ->willReturn(ConfigPathTypes::APP);
        $mockDModel2 = $this->getMockBuilder($className3)
                ->setMethods(['get', 'has'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockDModel2->expects($this->once())
                ->method('has')
                ->with($index)
                ->willReturn(false);
        $mockSu2 = $this->getMockBuilder($className2)
                ->setMethods(['getDataModel', 'getStorageModel'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSu2->expects($this->once())
                ->method('getStorageModel')
                ->willReturn($mockSModel2);
        $mockSu2->expects($this->once())
                ->method('getDataModel')
                ->willReturn($mockDModel2);

        $mockSModel3 = $this->getMockBuilder($className5)
                ->setMethods(['getConfigPathType'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSModel3->expects($this->once())
                ->method('getConfigPathType')
                ->willReturn(ConfigPathTypes::OVERRIDE);
        $mockDModel3 = $this->getMockBuilder($className3)
                ->setMethods(['get', 'has'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockDModel3->expects($this->once())
                ->method('has')
                ->with($index)
                ->willReturn(true);
        $mockDModel3->expects($this->once())
                ->method('get')
                ->with($index)
                ->willReturn($expected);
        $mockSu3 = $this->getMockBuilder($className2)
                ->setMethods(['getDataModel', 'getStorageModel'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSu3->expects($this->once())
                ->method('getStorageModel')
                ->willReturn($mockSModel3);
        $mockSu3->expects($this->exactly(2))
                ->method('getDataModel')
                ->willReturn($mockDModel3);

        $list = [
            $mockDummy,
            $mockSu1,
            $mockSu2,
            $mockSu3
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['get'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new ReflectionClass($className);
        $property = $reflectedClass->getProperty('items');
        $property->setAccessible(true);
        $property->setValue($mock, $list);

        $this->assertEquals($expected, $mock->getByConfigPathTypes($index, $cpts));
    }

    public function testGetByConfigPathTypesReturnDefault()
    {
        $className = StorageUnitList::class;
        $className2 = ConfigStorageUnit::class;
        $className3 = GenericConfigModel::class;
        $className4 = DummyStorageUnit::class;
        $className5 = ConfigFileModel::class;

        $index = '';
        $cpts = [
            ConfigPathTypes::APP,
            ConfigPathTypes::OVERRIDE,
            ConfigPathTypes::CACHE
        ];
        $expected = 'expected value, which is the default value';

        $mockDummy = $this->getMockBuilder($className4)
                ->setMethods(['getHash'])
                ->disableOriginalConstructor()
                ->getMock();

        $mockSModel1 = $this->getMockBuilder($className5)
                ->setMethods(['getConfigPathType'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSModel1->expects($this->once())
                ->method('getConfigPathType')
                ->willReturn(ConfigPathTypes::KERNEL);
        $mockSu1 = $this->getMockBuilder($className2)
                ->setMethods(['getStorageModel'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSu1->expects($this->once())
                ->method('getStorageModel')
                ->willReturn($mockSModel1);

        $mockSModel2 = $this->getMockBuilder($className5)
                ->setMethods(['getConfigPathType'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSModel2->expects($this->once())
                ->method('getConfigPathType')
                ->willReturn(ConfigPathTypes::APP);
        $mockDModel2 = $this->getMockBuilder($className3)
                ->setMethods(['has'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockDModel2->expects($this->once())
                ->method('has')
                ->with($index)
                ->willReturn(false);
        $mockSu2 = $this->getMockBuilder($className2)
                ->setMethods(['getDataModel', 'getStorageModel'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSu2->expects($this->once())
                ->method('getStorageModel')
                ->willReturn($mockSModel2);
        $mockSu2->expects($this->once())
                ->method('getDataModel')
                ->willReturn($mockDModel2);

        $mockSModel3 = $this->getMockBuilder($className5)
                ->setMethods(['getConfigPathType'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSModel3->expects($this->once())
                ->method('getConfigPathType')
                ->willReturn(ConfigPathTypes::OVERRIDE);
        $mockDModel3 = $this->getMockBuilder($className3)
                ->setMethods(['has'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockDModel3->expects($this->once())
                ->method('has')
                ->with($index)
                ->willReturn(false);
        $mockSu3 = $this->getMockBuilder($className2)
                ->setMethods(['getDataModel', 'getStorageModel'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSu3->expects($this->once())
                ->method('getStorageModel')
                ->willReturn($mockSModel3);
        $mockSu3->expects($this->once())
                ->method('getDataModel')
                ->willReturn($mockDModel3);

        $list = [
            $mockDummy,
            $mockSu1,
            $mockSu2,
            $mockSu3
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['get'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new ReflectionClass($className);
        $property = $reflectedClass->getProperty('items');
        $property->setAccessible(true);
        $property->setValue($mock, $list);

        $this->assertEquals($expected, $mock->getByConfigPathTypes($index, $cpts, $expected));
    }

    public function testGetByConfigPathTypesExcluded()
    {
        $className = StorageUnitList::class;
        $className2 = ConfigStorageUnit::class;
        $className4 = DummyStorageUnit::class;
        $className5 = ConfigFileModel::class;

        $mockDummy = $this->getMockBuilder($className4)
                ->setMethods(['getHash'])
                ->disableOriginalConstructor()
                ->getMock();

        $mockSModel1 = $this->getMockBuilder($className5)
                ->setMethods(['getConfigPathType'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSModel1->expects($this->once())
                ->method('getConfigPathType')
                ->willReturn(ConfigPathTypes::KERNEL);
        $mockSu1 = $this->getMockBuilder($className2)
                ->setMethods(['getStorageModel'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSu1->expects($this->once())
                ->method('getStorageModel')
                ->willReturn($mockSModel1);

        $mockSModel2 = $this->getMockBuilder($className5)
                ->setMethods(['getConfigPathType'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSModel2->expects($this->once())
                ->method('getConfigPathType')
                ->willReturn(ConfigPathTypes::APP);
        $mockSu2 = $this->getMockBuilder($className2)
                ->setMethods(['getStorageModel'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSu2->expects($this->once())
                ->method('getStorageModel')
                ->willReturn($mockSModel2);

        $mockSModel3 = $this->getMockBuilder($className5)
                ->setMethods(['getConfigPathType'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSModel3->expects($this->once())
                ->method('getConfigPathType')
                ->willReturn(ConfigPathTypes::OVERRIDE);
        $mockSu3 = $this->getMockBuilder($className2)
                ->setMethods(['getStorageModel'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSu3->expects($this->once())
                ->method('getStorageModel')
                ->willReturn($mockSModel3);

        $mockSModel4 = $this->getMockBuilder($className5)
                ->setMethods(['getConfigPathType'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSModel4->expects($this->once())
                ->method('getConfigPathType')
                ->willReturn(ConfigPathTypes::ADDON);
        $mockSu4 = $this->getMockBuilder($className2)
                ->setMethods(['getStorageModel'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSu4->expects($this->once())
                ->method('getStorageModel')
                ->willReturn($mockSModel4);

        $mockSModel5 = $this->getMockBuilder($className5)
                ->setMethods(['getConfigPathType'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSModel5->expects($this->once())
                ->method('getConfigPathType')
                ->willReturn(ConfigPathTypes::CACHE);
        $mockSu5 = $this->getMockBuilder($className2)
                ->setMethods(['getStorageModel'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSu5->expects($this->once())
                ->method('getStorageModel')
                ->willReturn($mockSModel5);

        $cpts = [
            ConfigPathTypes::APP,
            ConfigPathTypes::OVERRIDE
        ];
        $list = [
            $mockDummy,
            $mockSu1,
            $mockSu2,
            $mockSu3,
            $mockSu4,
            $mockSu5
        ];
        $expected = [
            $mockSu1,
            $mockSu4,
            $mockSu5
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['get'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new ReflectionClass($className);
        $property = $reflectedClass->getProperty('items');
        $property->setAccessible(true);
        $property->setValue($mock, $list);

        $this->assertEquals($expected, $mock->getByConfigPathTypesExcluded($cpts));
    }

    public function testGetByConfigPathTypeMax()
    {
        $className = StorageUnitList::class;
        $className2 = ConfigStorageUnit::class;
        $className3 = GenericConfigModel::class;
        $className4 = DummyStorageUnit::class;

        $index = 'definitions.test';
        $default = 'default value';
        $expected = 'searched value';

        $mockDummy = $this->getMockBuilder($className4)
                ->setMethods(['getHash'])
                ->disableOriginalConstructor()
                ->getMock();

        $mockSu1 = $this->getMockBuilder($className2)
                ->setMethods(['getStorageModel'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSu1->expects($this->any())
                ->method('getStorageModel')
                ->willReturn(false);

        $mockDModel2 = $this->getMockBuilder($className3)
                ->setMethods(['get', 'has'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockDModel2->expects($this->once())
                ->method('has')
                ->with($index)
                ->willReturn(true);
        $mockDModel2->expects($this->once())
                ->method('get')
                ->with($index)
                ->willReturn($expected);
        $mockSu2 = $this->getMockBuilder($className2)
                ->setMethods(['getDataModel'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSu2->expects($this->exactly(2))
                ->method('getDataModel')
                ->willReturn($mockDModel2);

        $mockDModel3 = $this->getMockBuilder($className3)
                ->setMethods(['has'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockDModel3->expects($this->once())
                ->method('has')
                ->with($index)
                ->willReturn(false);
        $mockSu3 = $this->getMockBuilder($className2)
                ->setMethods(['getDataModel'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSu3->expects($this->once())
                ->method('getDataModel')
                ->willReturn($mockDModel3);

        $mockSu4 = $this->getMockBuilder($className2)
                ->setMethods(['getStorageModel'])
                ->disableOriginalConstructor()
                ->getMock();

        $mockSu5 = $this->getMockBuilder($className2)
                ->setMethods(['getStorageModel'])
                ->disableOriginalConstructor()
                ->getMock();

        $list = [
            ConfigPathTypes::CACHE => $mockSu5,
            ConfigPathTypes::ADDON => $mockSu4,
            ConfigPathTypes::OVERRIDE => $mockSu3,
            ConfigPathTypes::APP => $mockSu2,
            ConfigPathTypes::ALL => $mockDummy,
            ConfigPathTypes::KERNEL => $mockSu1
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['get'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new ReflectionClass($className);
        $property = $reflectedClass->getProperty('items');
        $property->setAccessible(true);
        $property->setValue($mock, $list);

        $this->assertEquals($expected, $mock->getByConfigPathTypeMax($index, ConfigPathTypes::OVERRIDE, $default));
    }

    public function testGetByConfigPathTypeMaxReturnDefault()
    {
        $className = StorageUnitList::class;
        $className2 = ConfigStorageUnit::class;
        $className3 = GenericConfigModel::class;
        $className4 = DummyStorageUnit::class;

        $index = 'definitions.test';
        $default = 'default value';
        $expected = $default;

        $mockDummy = $this->getMockBuilder($className4)
                ->setMethods(['getHash'])
                ->disableOriginalConstructor()
                ->getMock();

        $mockDModel1 = $this->getMockBuilder($className3)
                ->setMethods(['has'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockDModel1->expects($this->once())
                ->method('has')
                ->with($index)
                ->willReturn(false);
        $mockSu1 = $this->getMockBuilder($className2)
                ->setMethods(['getDataModel'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSu1->expects($this->once())
                ->method('getDataModel')
                ->willReturn($mockDModel1);

        $mockDModel2 = $this->getMockBuilder($className3)
                ->setMethods(['has'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockDModel2->expects($this->once())
                ->method('has')
                ->with($index)
                ->willReturn(false);
        $mockSu2 = $this->getMockBuilder($className2)
                ->setMethods(['getDataModel'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSu2->expects($this->once())
                ->method('getDataModel')
                ->willReturn($mockDModel2);

        $mockDModel3 = $this->getMockBuilder($className3)
                ->setMethods(['has'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockDModel3->expects($this->once())
                ->method('has')
                ->with($index)
                ->willReturn(false);
        $mockSu3 = $this->getMockBuilder($className2)
                ->setMethods(['getDataModel'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSu3->expects($this->once())
                ->method('getDataModel')
                ->willReturn($mockDModel3);

        $mockSu4 = $this->getMockBuilder($className2)
                ->setMethods(['getStorageModel'])
                ->disableOriginalConstructor()
                ->getMock();

        $mockSu5 = $this->getMockBuilder($className2)
                ->setMethods(['getStorageModel'])
                ->disableOriginalConstructor()
                ->getMock();

        $list = [
            ConfigPathTypes::CACHE => $mockSu5,
            ConfigPathTypes::ADDON => $mockSu4,
            ConfigPathTypes::OVERRIDE => $mockSu3,
            ConfigPathTypes::APP => $mockSu2,
            ConfigPathTypes::ALL => $mockDummy,
            ConfigPathTypes::KERNEL => $mockSu1
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['get'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new ReflectionClass($className);
        $property = $reflectedClass->getProperty('items');
        $property->setAccessible(true);
        $property->setValue($mock, $list);

        $this->assertEquals($expected, $mock->getByConfigPathTypeMax($index, ConfigPathTypes::OVERRIDE, $default));
    }

    public function testGetNext()
    {
        $className = StorageUnitList::class;

        $list = [
            'test_1' => 'test 1',
            'test_2' => 'test 2',
            'test_3' => 'test 3',
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getHash'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new ReflectionClass($className);
        $property = $reflectedClass->getProperty('items');
        $property->setAccessible(true);
        $property->setValue($mock, $list);

        $this->assertEquals('test 1', $mock->getNext('test_2'));
    }

    public function testGetPrevious()
    {
        $className = StorageUnitList::class;

        $list = [
            'test_1' => 'test 1',
            'test_2' => 'test 2',
            'test_3' => 'test 3',
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getHash'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new ReflectionClass($className);
        $property = $reflectedClass->getProperty('items');
        $property->setAccessible(true);
        $property->setValue($mock, $list);

        $this->assertEquals('test 3', $mock->getPrevious('test_2'));
    }

}
