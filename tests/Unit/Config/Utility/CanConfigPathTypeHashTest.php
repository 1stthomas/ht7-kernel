<?php

namespace Ht7\Kernel\Tests\Config\Storage\Utility;

use \ReflectionClass;
use \Ht7\Kernel\Config\ConfigPathTypes;
use \Ht7\Kernel\Config\Utility\CanConfigPathTypeHash;
use \PHPUnit\Framework\TestCase;

class CanConfigPathTypeHashTest extends TestCase
{

    public function testGetHash()
    {
        $className = CanConfigPathTypeHash::class;

        $mock = $this->getMockForTrait($className);

        $reflectedClass = new ReflectionClass($mock);
        $property = $reflectedClass->getProperty('configPathType');
        $property->setAccessible(true);
        $property->setValue($mock, ConfigPathTypes::OVERRIDE);

        $this->assertEquals(ConfigPathTypes::OVERRIDE, $mock->getHash());
    }

}
