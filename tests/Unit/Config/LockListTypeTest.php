<?php

namespace Ht7\Kernel\Tests\Config;

use \ReflectionClass;
use \Ht7\Kernel\Config\LockListType;
use \Ht7\Kernel\Config\ConfigPathTypes;
use \PHPUnit\Framework\TestCase;

class LockListTypeTest extends TestCase
{

    public function testConstruct()
    {
        $className = LockListType::class;

        $locks = [
            'defaults.test1',
            'definitions.test1'
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['load'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('load')
                ->with($locks);

        $reflectedClass = new ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, ConfigPathTypes::KERNEL, $locks);
        $property = $reflectedClass->getProperty('configPathType');
        $property->setAccessible(true);

        $this->assertEquals(ConfigPathTypes::KERNEL, $property->getValue($mock));
    }

}
