<?php

namespace Ht7\Kernel\Tests\Config\Storage;

use \InvalidArgumentException;
use \ReflectionClass;
use \Ht7\Kernel\Config\ConfigLoadingSequence;
use \Ht7\Kernel\Config\ConfigPathTypes;
use \Ht7\Kernel\Config\Models\ConfigFileModel;
use \Ht7\Kernel\Config\Models\ConfigDefinitionsModel;
use \Ht7\Kernel\Config\Models\GenericConfigModel;
use \Ht7\Kernel\Config\Storage\ConfigDefinitionsStorageUnit;
use \Ht7\Kernel\Config\Storage\DummyStorageUnit;
use \PHPUnit\Framework\TestCase;

class ConfigDefinitionsStorageUnitTest extends TestCase
{

    public function testGetDataModel()
    {
        $className = ConfigDefinitionsStorageUnit::class;

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

    public function testSetDataModel()
    {
        $className = ConfigDefinitionsStorageUnit::class;
        $className2 = ConfigDefinitionsModel::class;

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
        $className = ConfigDefinitionsStorageUnit::class;
        $className2 = GenericConfigModel::class;

        $mockDModel = $this->getMockBuilder($className2)
                ->setMethods(['getDataModel'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getStorageModel'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('/must be an instance of(.)+' . get_class(ConfigDefinitionsModel) . '(.)+found(.)+' . get_class($mockDModel) . '/');

        $mock->setDataModel($mockDModel);
    }

}
