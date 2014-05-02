<?php

namespace UdbTest\Domain\Entity;

use Udb\Domain\Entity\Uid;


class UidTest extends \PHPUnit_Framework_TestCase
{


    public function testConstructorWithInvalidValueType()
    {
        $this->setExpectedException('Udb\Domain\Entity\Exception\InvalidValueException', 'Invalid value');
        $uid = new Uid(array(
            'foo'
        ));
    }


    public function testConstructorWithInvalidStringValue()
    {
        $this->setExpectedException('Udb\Domain\Entity\Exception\InvalidValueException', 'Invalid string value');
        
        $uid = new Uid('abc.*');
    }
    
    
    public function testConstructorWithAlphaNumericValue()
    {
        $uid = new Uid('testuser12');
        
        $this->assertSame('testuser12', $uid->getValue());
    }
}