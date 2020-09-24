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
use \PHPUnit\Framework\TestCase;

class ConfigStorageUnitTest extends TestCase
{

    public function testGetHash()
    {
        $className = ConfigStorageUnit::class;
        $className2 = ConfigFileModel::class;

        $mockSModel = $this->getMockBuilder($className2)
                ->setMethods(['getConfigPathType'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockSModel->expects($this->once())
                ->method('getConfigPathType')
                ->willReturn(ConfigPathTypes::OVERRIDE);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getStorageModel'])
                ->disableOriginalConstructor()
                ->getMock();
        $mock->expects($this->once())
                ->method('getStorageModel')
                ->willReturn($mockSModel);

        $this->assertEquals(ConfigPathTypes::OVERRIDE, $mock->getHash());
    }

    public function testGetDataModel()
    {
        $className = ConfigStorageUnit::class;

        $expected = 'test_1';

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getStorageModel'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new ReflectionClass($className);
        $property = $reflectedClass->getProperty('dataModel');
        $property->setAccessible(true);
        $property->setValue($mock, $expected);

        $this->assertEquals($expected, $mock->getDataModel());
    }

    public function testGetStorageModel()
    {
        $className = ConfigStorageUnit::class;

        $expected = 'test_1';

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getDataModel'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new ReflectionClass($className);
        $property = $reflectedClass->getProperty('storageModel');
        $property->setAccessible(true);
        $property->setValue($mock, $expected);

        $this->assertEquals($expected, $mock->getStorageModel());
    }

    public function testSetDataModel()
    {
        $className = ConfigStorageUnit::class;
        $className2 = GenericConfigModel::class;

        $mockDModel = $this->getMockBuilder($className2)
                ->setMethods(['getDataModel'])
                ->disableOriginalConstructor()
                ->getMock();

        $expected = $mockDModel;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getStorageModel'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->setDataModel($mockDModel);

        $reflectedClass = new ReflectionClass($className);
        $property = $reflectedClass->getProperty('dataModel');
        $property->setAccessible(true);

        $this->assertEquals($expected, $property->getValue($mock));
    }

    public function testSetDataModelWithException()
    {
        $className = ConfigStorageUnit::class;

        $mockDModel = $this->getMockBuilder($className)
                ->setMethods(['getDataModel'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getStorageModel'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('/must be an instance of(.)+' . get_class(GenericConfigModel) . '(.)+found(.)+' . get_class($mockDModel) . '/');

        $mock->setDataModel($mockDModel);
    }

    public function testSetStorageModel()
    {
        $className = ConfigStorageUnit::class;
        $className2 = ConfigFileModel::class;

        $mockSModel = $this->getMockBuilder($className2)
                ->setMethods(['getDataModel'])
                ->disableOriginalConstructor()
                ->getMock();

        $expected = $mockSModel;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getDataModel'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->setStorageModel($mockSModel);

        $reflectedClass = new ReflectionClass($className);
        $property = $reflectedClass->getProperty('storageModel');
        $property->setAccessible(true);

        $this->assertEquals($expected, $property->getValue($mock));
    }

    public function testSetStorageModelWithException()
    {
        $className = ConfigStorageUnit::class;

        $mockSModel = $this->getMockBuilder($className)
                ->setMethods(['getDataModel'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getDataModel'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('/must be an instance of(.)+' . get_class(ConfigFileModel) . '(.)+found(.)+' . get_class($mockSModel) . '/');

        $mock->setStorageModel($mockSModel);
    }

}
