<?php

namespace Ht7\Kernel\Tests\Utility\Values\Sanitizers;

use \PHPUnit\Framework\TestCase;
use \Ht7\Kernel\Kernel;
use \Ht7\Kernel\Utility\Values\Sanitizers\DefaultSanitizer;

class DefaultSanitizerTest extends TestCase
{

    public function testGetType()
    {
        $className = DefaultSanitizer::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['is'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->assertEquals('default', $mock->getType());
    }

    public function testIs()
    {
        $className = DefaultSanitizer::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setOptions'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->assertTrue($mock->is(false));
        $this->assertTrue($mock->is(true));
        $this->assertTrue($mock->is('string'));
        $this->assertTrue($mock->is('"'));
        $this->assertTrue($mock->is('\"'));
        $this->assertTrue($mock->is('\''));
        $this->assertTrue($mock->is('123'));
        $this->assertTrue($mock->is('123.123'));
        $this->assertTrue($mock->is(Kernel::class));
        $this->assertTrue($mock->is(100));
        $this->assertTrue($mock->is(10.123));
        $this->assertTrue($mock->is(0));
        $this->assertTrue($mock->is(1e3));
        $this->assertTrue($mock->is(null));
        $this->assertTrue($mock->is((new \stdClass())));
    }

    public function testSanitize()
    {
        $className = DefaultSanitizer::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['is'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->assertTrue($mock->sanitize(true, 0));
        $this->assertFalse($mock->sanitize(false, 100));
        $this->assertEquals('true', $mock->sanitize('true', 10));
        $this->assertEquals('false', $mock->sanitize("false", 5));
        $this->assertEquals('"', $mock->sanitize('"', 2));
        $this->assertEquals('\"', $mock->sanitize('\"', 3));
        $this->assertEquals('123', $mock->sanitize('123', 4));
        $this->assertEquals('123.123', $mock->sanitize('123.123', 5));
        $this->assertEquals(Kernel::class, $mock->sanitize(Kernel::class, 6));
        $this->assertEquals(123, $mock->sanitize(123, 7));
        $this->assertEquals(123.123, $mock->sanitize(123.123, 8));
        $this->assertEquals(0, $mock->sanitize(0, 9));
        $this->assertEquals(1e3, $mock->sanitize(1e3, 9));
        $this->assertEquals(null, $mock->sanitize(null, 9));
        $this->assertEquals((new \stdClass()), $mock->sanitize((new \stdClass()), 9));
    }

}
