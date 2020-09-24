<?php

namespace Ht7\Kernel\Tests\Config\Storage;

use \ReflectionClass;
use \Ht7\Kernel\Config\Models\ConfigFileModel;
use \Ht7\Kernel\Config\Models\GenericConfigModel;
use \Ht7\Kernel\Config\Storage\ConfigStorageUnit;
use \Ht7\Kernel\Config\Storage\GenericConfigIn;
use \PHPUnit\Framework\TestCase;

class GenericConfigInTest extends TestCase
{

    public function testConstruct()
    {
        $className = GenericConfigIn::class;
        $className2 = ConfigFileModel::class;

        $dataModel = 'data';
        $storageModel = $this->getMockBuilder($className2)
                ->setMethods(['setConfigPathType'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setDataModel', 'setStorageModel'])
                ->disableOriginalConstructor()
                ->getMock();
        $mock->expects($this->once())
                ->method('setDataModel')
                ->with($dataModel);
        $mock->expects($this->once())
                ->method('setStorageModel')
                ->with($storageModel);

        $reflectedClass = new ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, $dataModel, $storageModel);
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

}
