<?php

namespace Ht7\Kernel\Tests\Utility\Values\Sanitizers;

use \ReflectionClass;
use \PHPUnit\Framework\TestCase;
use \Ht7\Kernel\Utility\Values\Sanitizers\SanitizerListItem;
use \Ht7\Kernel\Utility\Values\Sanitizers\DefaultSanitizer;

class SanitizerListItemTest extends TestCase
{

    public function testConstructor()
    {
        // see: http://miljar.github.io/blog/2013/12/20/phpunit-testing-the-constructor/
        $className = SanitizerListItem::class;

        $sanitizer = new DefaultSanitizer();
        $flags = 10;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setFlags', 'setSanitizer'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('setFlags')
                ->with($this->equalTo($flags));
        $mock->expects($this->once())
                ->method('setSanitizer')
                ->with($this->equalTo($sanitizer));

        $reflectedClass = new ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, $sanitizer, $flags);
    }

    public function testGetFlags()
    {
        $className = SanitizerListItem::class;

        $flags = 5;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setFlags'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new ReflectionClass($className);
        $prop = $reflectedClass->getProperty('flags');
        $prop->setAccessible(true);

        $prop->setValue($mock, $flags);

        $this->assertEquals($flags, $mock->getFlags());
    }

    public function testGetSanitizer()
    {
        $className = SanitizerListItem::class;

        $sanitizer = new DefaultSanitizer();

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setSanitizer'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new ReflectionClass($className);
        $prop = $reflectedClass->getProperty('sanitizer');
        $prop->setAccessible(true);

        $prop->setValue($mock, $sanitizer);

        $this->assertEquals($sanitizer, $mock->getSanitizer());
    }

    public function testSetFlags()
    {
        $className = SanitizerListItem::class;

        $flags = 5;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getFlags'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new ReflectionClass($className);
        $prop = $reflectedClass->getProperty('flags');
        $prop->setAccessible(true);

        $mock->setFlags($flags);

        $this->assertEquals($flags, $prop->getValue($mock));
    }

    public function testSetSanitizer()
    {
        $className = SanitizerListItem::class;

        $sanitizer = new DefaultSanitizer();

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getSanitizer'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new ReflectionClass($className);
        $prop = $reflectedClass->getProperty('sanitizer');
        $prop->setAccessible(true);

        $mock->setSanitizer($sanitizer);

        $this->assertEquals($sanitizer, $prop->getValue($mock));
    }

}
