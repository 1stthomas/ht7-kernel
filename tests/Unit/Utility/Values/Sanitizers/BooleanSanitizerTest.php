<?php

namespace Ht7\Kernel\Tests\Utility\Values\Sanitizers;

use \PHPUnit\Framework\TestCase;
use \Ht7\Kernel\Kernel;
use \Ht7\Kernel\Utility\Values\Sanitizers\BooleanSanitizer;
use \Ht7\Kernel\Utility\Values\Sanitizers\BooleanSanitizeTypes;

class BooleanSanitizerTest extends TestCase
{

    public function testGetType()
    {
        $className = BooleanSanitizer::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['is'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->assertEquals('bool', $mock->getType());
    }

    public function testIs()
    {
        $className = BooleanSanitizer::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setOptions'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->assertTrue($mock->is(false));
        $this->assertTrue($mock->is(true));

        $this->assertFalse($mock->is('string'));
        $this->assertFalse($mock->is('"'));
        $this->assertFalse($mock->is('\"'));
        $this->assertFalse($mock->is('\''));
        $this->assertFalse($mock->is('123'));
        $this->assertFalse($mock->is('123.123'));
        $this->assertFalse($mock->is(Kernel::class));
        $this->assertFalse($mock->is(100));
        $this->assertFalse($mock->is(10.123));
        $this->assertFalse($mock->is(0));
        $this->assertFalse($mock->is(1e3));
        $this->assertFalse($mock->is(null));
        $this->assertFalse($mock->is((new \stdClass())));
    }

    public function testSanitize()
    {
        $className = BooleanSanitizer::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['is'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->assertEquals(1, $mock->sanitize(true, BooleanSanitizeTypes::NUMERICAL));
        $this->assertEquals(0, $mock->sanitize(false, BooleanSanitizeTypes::NUMERICAL));
        $this->assertEquals('true', $mock->sanitize(true, BooleanSanitizeTypes::TRUE_FALSE_LOWERCASE));
        $this->assertEquals('false', $mock->sanitize(false, BooleanSanitizeTypes::TRUE_FALSE_LOWERCASE));
        $this->assertEquals('TRUE', $mock->sanitize(true, BooleanSanitizeTypes::TRUE_FALSE_UPPERCASE));
        $this->assertEquals('FALSE', $mock->sanitize(false, BooleanSanitizeTypes::TRUE_FALSE_UPPERCASE));

        $this->assertEquals(null, $mock->sanitize('true', 1000));
        $this->assertEquals(null, $mock->sanitize('false', 10000));
    }

}
