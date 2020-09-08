<?php

namespace Ht7\Kernel\Tests\Utility\Values\Sanitizers;

use \ReflectionClass;
use \PHPUnit\Framework\TestCase;
use \Ht7\Kernel\Utility\Values\Sanitizers\BooleanSanitizer;
use \Ht7\Kernel\Utility\Values\Sanitizers\BooleanSanitizeTypes;
use \Ht7\Kernel\Utility\Values\Sanitizers\SanitizerList;
use \Ht7\Kernel\Utility\Values\Sanitizers\SanitizerListItem;
use \Ht7\Kernel\Utility\Values\Sanitizers\DefaultSanitizer;
use \Ht7\Kernel\Utility\Values\Sanitizers\StringSanitizer;
use \Ht7\Kernel\Utility\Values\Sanitizers\StringSanitizeTypes;

class SanitizerListTest extends TestCase
{

    public function testAdd()
    {
        $className = SanitizerList::class;

        $item = new SanitizerListItem(
                (new DefaultSanitizer()),
                10
        );

        $mock = $this->getMockBuilder($className)
                ->setMethods(['load'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new ReflectionClass($className);
        $prop = $reflectedClass->getProperty('items');
        $prop->setAccessible(true);

        $mock->add($item);

        $prop->getValue($mock);

        $this->assertEquals($item, $prop->getValue($mock)[0]);
    }

    public function testAddWithException()
    {
        $className = SanitizerList::class;

        $item = new \stdClass();

        $mock = $this->getMockBuilder($className)
                ->setMethods(['load'])
                ->disableOriginalConstructor()
                ->getMock();

        $this->expectException(\InvalidArgumentException::class);

        $mock->add($item);
    }

    public function testAddWithExceptionMessage()
    {
        $className = SanitizerList::class;

        $item = new \stdClass();

        $mock = $this->getMockBuilder($className)
                ->setMethods(['load'])
                ->disableOriginalConstructor()
                ->getMock();

        try {
            $mock->add($item);
        } catch (\InvalidArgumentException $e) {
            $this->assertContains(
                    '\\Ht7\\Kernel\\Utility\\Values\\Sanitizers\\SanitizerListItem',
                    $e->getMessage()
            );
        }
    }

    public function testAddIndividually()
    {
        $className = SanitizerList::class;

        $sanitizer = new StringSanitizer([]);
        $flagsEvaluated = StringSanitizeTypes::ADD_QUOTATION_MARKS | StringSanitizeTypes::ENCODE_QUOTATION_MARKS | StringSanitizeTypes::KEEP_CLASS_DEFINITIONS;
        $item = $item = new SanitizerListItem($sanitizer, $flagsEvaluated);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['add'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('add')
                ->with($this->equalTo($item));

        $mock->addIndividually($sanitizer, $flagsEvaluated);
    }

    public function testAddIndividuallyByFlagArray()
    {
        $className = SanitizerList::class;

        $sanitizer = new StringSanitizer([]);
        $flags = [
            StringSanitizeTypes::ADD_QUOTATION_MARKS,
            StringSanitizeTypes::ENCODE_QUOTATION_MARKS,
            StringSanitizeTypes::KEEP_CLASS_DEFINITIONS
        ];
        $flagsEvaluated = StringSanitizeTypes::ADD_QUOTATION_MARKS | StringSanitizeTypes::ENCODE_QUOTATION_MARKS | StringSanitizeTypes::KEEP_CLASS_DEFINITIONS;
        $item = $item = new SanitizerListItem($sanitizer, $flagsEvaluated);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['add'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->once())
                ->method('add')
                ->with($this->equalTo($item));

        $mock->addIndividually($sanitizer, $flags);
    }

    public function testLoad()
    {
        $className = SanitizerList::class;

        $item1 = new SanitizerListItem(
                (new StringSanitizer([])),
                StringSanitizeTypes::ADD_QUOTATION_MARKS | StringSanitizeTypes::KEEP_CLASS_DEFINITIONS
        );
        $item2 = new SanitizerListItem(
                (new DefaultSanitizer()),
                5
        );
        $sanitizer = new BooleanSanitizer();
        $flags = BooleanSanitizeTypes::TRUE_FALSE_LOWERCASE;

        $data = [
            $item1,
            $item2,
            [
                $sanitizer,
                $flags
            ]
        ];

        $mock = $this->getMockBuilder($className)
                ->setMethods(['add', 'addIndividually'])
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->exactly(2))
                ->method('add')
                ->withConsecutive([$this->equalTo($item1)], [$this->equalTo($item2)]);
        $mock->expects($this->once())
                ->method('addIndividually')
                ->with($sanitizer, $flags);

        $reflectedClass = new ReflectionClass($className);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($mock, $data);
    }

    public function testSanitize()
    {
        $className = SanitizerList::class;

        $string = 'test string';
        $flags = StringSanitizeTypes::ADD_QUOTATION_MARKS | StringSanitizeTypes::ENCODE_QUOTATION_MARKS;

        $stubedBS = $this->createMock(BooleanSanitizer::class);
        $stubedBS->method('is')
                ->willReturn(false);
        $stubedDS = $this->createMock(DefaultSanitizer::class);
        $stubedDS->method('is')
                ->willReturn(false);

        $mockedSS = $this->getMockBuilder(StringSanitizer::class)
                ->setMethods(['is', 'sanitize'])
                ->disableOriginalConstructor()
                ->getMock();

        $mockedSS->expects($this->once())
                ->method('is')
                ->with($this->equalTo($string))
                ->willReturn(true);
        $mockedSS->expects($this->once())
                ->method('sanitize')
                ->with($string, $flags)
                ->willReturn('"test string"');

        $reflectedClassSl = new ReflectionClass(SanitizerListItem::class);
        $sanitizerProperty = $reflectedClassSl->getProperty('sanitizer');
        $sanitizerProperty->setAccessible(true);
        $flagsProperty = $reflectedClassSl->getProperty('flags');
        $flagsProperty->setAccessible(true);

        $mockedItem1 = $this->getMockBuilder(SanitizerListItem::class)
                ->setMethods(['getSanitizer'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockedItem1->expects($this->any())
                ->method('getSanitizer')
                ->willReturn($stubedBS);
        $sanitizerProperty->setValue($mockedItem1, $stubedBS);
        $flagsProperty->setValue($mockedItem1, 0);

        $mockedItem2 = $this->getMockBuilder(SanitizerListItem::class)
                ->setMethods(['getSanitizer'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockedItem2->expects($this->any())
                ->method('getSanitizer')
                ->willReturn($stubedDS);
        $sanitizerProperty->setValue($mockedItem2, $stubedDS);
        $flagsProperty->setValue($mockedItem2, 0);

        $mockedItem3 = $this->getMockBuilder(SanitizerListItem::class)
                ->setMethods(['getSanitizer', 'getFlags'])
                ->disableOriginalConstructor()
                ->getMock();
        $mockedItem3->expects($this->any())
                ->method('getSanitizer')
                ->willReturn($mockedSS);
        $mockedItem3->expects($this->once())
                ->method('getFlags')
                ->willReturn($flags);
        $sanitizerProperty->setValue($mockedItem3, $mockedSS);
        $flagsProperty->setValue($mockedItem3, $flags);

        $mock = $this->getMockBuilder($className)
                ->setMethods(['add'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new ReflectionClass($className);
        $prop = $reflectedClass->getProperty('items');
        $prop->setAccessible(true);

        $prop->setValue($mock, [$mockedItem1, $mockedItem2, $mockedItem3]);

        $this->assertEquals('"test string"', $mock->sanitize($string));
    }

    public function testSanitizeNoMatch()
    {
        $className = SanitizerList::class;

        $mock = $this->getMockBuilder($className)
                ->setMethods(['add'])
                ->disableOriginalConstructor()
                ->getMock();

        $reflectedClass = new ReflectionClass($className);
        $prop = $reflectedClass->getProperty('items');
        $prop->setAccessible(true);

        $prop->setValue($mock, []);

        $this->assertEquals(null, $mock->sanitize('test string'));
    }

}
