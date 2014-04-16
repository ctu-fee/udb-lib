<?php

namespace UdbTest\Domain\Entity;


class AbstractStringValueObjectTest extends \PHPUnit_Framework_TestCase
{


    public function testConstructor()
    {
        $value = 'foo';
        
        $valueObject = $this->getMockBuilder('Udb\Domain\Entity\AbstractStringValueObject')
            ->setConstructorArgs(array(
            $value
        ))
            ->getMockForAbstractClass();
        
        $this->assertSame($value, $valueObject->getValue());
    }


    public function testToString()
    {
        $value = 'foo';
        
        $valueObject = $this->getMockBuilder('Udb\Domain\Entity\AbstractStringValueObject')
            ->setConstructorArgs(array(
            $value
        ))
            ->getMockForAbstractClass();
        
        $this->assertSame($value, "$valueObject");
    }
}