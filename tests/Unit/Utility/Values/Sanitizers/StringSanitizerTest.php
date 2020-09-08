<?php

namespace Ht7\Kernel\Tests\Utility\Values\Sanitizers;

use \ReflectionClass;
use \PHPUnit\Framework\TestCase;
use \Ht7\Kernel\Kernel;
use \Ht7\Kernel\Utility\Values\Sanitizers\StringSanitizer;
use \Ht7\Kernel\Utility\Values\Sanitizers\StringSanitizeTypes;

class StringSanitizerTest extends TestCase
{

    public function testConstructor()
    {
        // see: http://miljar.github.io/blog/2013/12/20/phpunit-testing-the-constructor/
        $className = StringSanitizer::class;

        $options = [
            'test_01' => 'testtest',
            'test_02' => 'testtesttest',
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setOptions'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('setOptions')
                ->with($this->equalTo($options));

        $reflectedClass = new ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, $options);
    }

    public function testGetOption()
    {
        $className = StringSanitizer::class;

        $options = [
            'test_01' => 'testtest',
            'test_02' => 'testtesttest',
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setOptions'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new ReflectionClass($className);
        $prop = $reflectedClass->getProperty('options');
        $prop->setAccessible(true);

        $prop->setValue($mock, $options);

        $this->assertEquals($options['test_02'], $mock->getOption('test_02'));
    }

    public function testGetOptions()
    {
        $className = StringSanitizer::class;

        $options = [
            'test_01' => 'testtest',
            'test_02' => 'testtesttest',
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setOptions'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new ReflectionClass($className);
        $prop = $reflectedClass->getProperty('options');
        $prop->setAccessible(true);

        $prop->setValue($mock, $options);

        $this->assertEquals($options, $mock->getOptions());
    }

    public function testSetOptions()
    {
        $className = StringSanitizer::class;

        $options = [
            'test_01' => 'testtest',
            'test_02' => 'testtesttest',
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['getOptions'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->setOptions($options);

        $reflectedClass = new ReflectionClass($className);
        $prop = $reflectedClass->getProperty('options');
        $prop->setAccessible(true);

        $prop->setValue($mock, $options);

        $this->assertEquals($options, $prop->getValue($mock));
    }

    public function testGetType()
    {
        $className = StringSanitizer::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setOptions'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->assertEquals('string', $mock->getType());
    }

    public function testIs()
    {
        $className = StringSanitizer::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setOptions'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->assertTrue($mock->is('string'));
        $this->assertTrue($mock->is('"'));
        $this->assertTrue($mock->is('\"'));
        $this->assertTrue($mock->is('\''));
        $this->assertTrue($mock->is('123'));
        $this->assertTrue($mock->is('123.123'));
        $this->assertTrue($mock->is(Kernel::class));
        $this->assertFalse($mock->is(100));
        $this->assertFalse($mock->is(10.123));
        $this->assertFalse($mock->is(0));
        $this->assertFalse($mock->is(1e3));
        $this->assertFalse($mock->is(false));
        $this->assertFalse($mock->is(true));
        $this->assertFalse($mock->is(null));
        $this->assertFalse($mock->is((new \stdClass())));
    }

    public function testSanitize()
    {
        $className = StringSanitizer::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['setOptions'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new ReflectionClass($className);
        $prop = $reflectedClass->getProperty('options');
        $prop->setAccessible(true);
        $prop->setValue($mock, ['quotation_mark' => "'"]);

        $this->assertEquals("'string'", $mock->sanitize('string', StringSanitizeTypes::ALL));
        $this->assertEquals('Ht7\Kernel\Kernel::class', $mock->sanitize(Kernel::class, StringSanitizeTypes::ALL));
        $this->assertEquals("'\''", $mock->sanitize("'", StringSanitizeTypes::ALL));
        $this->assertEquals('\'"\'', $mock->sanitize('"', StringSanitizeTypes::ALL));
        $this->assertEquals("'\''", $mock->sanitize("`", StringSanitizeTypes::ALL));
        $this->assertEquals("'\''", $mock->sanitize("´", StringSanitizeTypes::ALL));

        $this->assertEquals("'string'", $mock->sanitize('string', StringSanitizeTypes::ENCODE_QUOTATION_MARKS | StringSanitizeTypes::KEEP_CLASS_DEFINITIONS | StringSanitizeTypes::SANITIZE_QUOTATION_MARKS | StringSanitizeTypes::ADD_QUOTATION_MARKS));
        $this->assertEquals('Ht7\Kernel\Kernel::class', $mock->sanitize(Kernel::class, StringSanitizeTypes::ENCODE_QUOTATION_MARKS | StringSanitizeTypes::KEEP_CLASS_DEFINITIONS | StringSanitizeTypes::SANITIZE_QUOTATION_MARKS | StringSanitizeTypes::ADD_QUOTATION_MARKS));
        $this->assertEquals("'\''", $mock->sanitize("'", StringSanitizeTypes::ENCODE_QUOTATION_MARKS | StringSanitizeTypes::KEEP_CLASS_DEFINITIONS | StringSanitizeTypes::SANITIZE_QUOTATION_MARKS | StringSanitizeTypes::ADD_QUOTATION_MARKS));
        $this->assertEquals('\'"\'', $mock->sanitize('"', StringSanitizeTypes::ENCODE_QUOTATION_MARKS | StringSanitizeTypes::KEEP_CLASS_DEFINITIONS | StringSanitizeTypes::SANITIZE_QUOTATION_MARKS | StringSanitizeTypes::ADD_QUOTATION_MARKS));
        $this->assertEquals("'\''", $mock->sanitize("`", StringSanitizeTypes::ENCODE_QUOTATION_MARKS | StringSanitizeTypes::KEEP_CLASS_DEFINITIONS | StringSanitizeTypes::SANITIZE_QUOTATION_MARKS | StringSanitizeTypes::ADD_QUOTATION_MARKS));
        $this->assertEquals("'\''", $mock->sanitize("´", StringSanitizeTypes::ENCODE_QUOTATION_MARKS | StringSanitizeTypes::KEEP_CLASS_DEFINITIONS | StringSanitizeTypes::SANITIZE_QUOTATION_MARKS | StringSanitizeTypes::ADD_QUOTATION_MARKS));

        $this->assertEquals("'string'", $mock->sanitize('string', StringSanitizeTypes::ADD_QUOTATION_MARKS));
        $this->assertEquals("'string'", $mock->sanitize("string", StringSanitizeTypes::ADD_QUOTATION_MARKS));
        $this->assertEquals('string', $mock->sanitize('string', StringSanitizeTypes::ENCODE_QUOTATION_MARKS | StringSanitizeTypes::KEEP_CLASS_DEFINITIONS | StringSanitizeTypes::SANITIZE_QUOTATION_MARKS));
        $this->assertEquals('string', $mock->sanitize('string', StringSanitizeTypes::ENCODE_QUOTATION_MARKS | StringSanitizeTypes::KEEP_CLASS_DEFINITIONS));
        $this->assertEquals('string', $mock->sanitize('string', StringSanitizeTypes::ENCODE_QUOTATION_MARKS));

        $this->assertEquals('Ht7\Kernel\Kernel::class', $mock->sanitize(Kernel::class, StringSanitizeTypes::KEEP_CLASS_DEFINITIONS));
        $this->assertEquals('\'Ht7\Kernel\Kernel\'', $mock->sanitize(Kernel::class, StringSanitizeTypes::ENCODE_QUOTATION_MARKS | StringSanitizeTypes::SANITIZE_QUOTATION_MARKS | StringSanitizeTypes::ADD_QUOTATION_MARKS));

        $this->assertEquals('"', $mock->sanitize('"', StringSanitizeTypes::SANITIZE_QUOTATION_MARKS));
        $this->assertEquals('\'', $mock->sanitize("'", StringSanitizeTypes::SANITIZE_QUOTATION_MARKS));
        $this->assertEquals('\\\'', $mock->sanitize("'", StringSanitizeTypes::ENCODE_QUOTATION_MARKS));
        $this->assertEquals('\'"\'', $mock->sanitize('"', StringSanitizeTypes::ADD_QUOTATION_MARKS));
        $this->assertEquals('"', $mock->sanitize('"', StringSanitizeTypes::KEEP_CLASS_DEFINITIONS));

        $prop->setValue($mock, ['quotation_mark' => '"']);

        $this->assertEquals('"string"', $mock->sanitize('string', StringSanitizeTypes::ALL));
        $this->assertEquals('"\'"', $mock->sanitize("'", StringSanitizeTypes::ALL));
        $this->assertEquals('"\""', $mock->sanitize('"', StringSanitizeTypes::ALL));
        $this->assertEquals('"\""', $mock->sanitize("`", StringSanitizeTypes::ALL));
        $this->assertEquals('"\""', $mock->sanitize("´", StringSanitizeTypes::ALL));
    }

}
