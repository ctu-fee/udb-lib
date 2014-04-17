<?php

namespace UdbTest\Domain\Entity;

use Udb\Domain\Entity\EmailAddress;


class EmailAddressTest extends \PHPUnit_Framework_TestCase
{


    public function testConstructorWithValidString()
    {
        $value = 'foo@bar';
        
        $email = new EmailAddress($value);
        $this->assertSame($value, $email->getValue());
    }


    public function testConstructorWithInvalidString()
    {
        $this->setExpectedException('Udb\Domain\Entity\Exception\InvalidValueException', 'Inivalid value format');
        
        $email = new EmailAddress('invalid');
    }


    public function testConstructorWithNonScalarValue()
    {
        $this->setExpectedException('Udb\Domain\Entity\Exception\InvalidValueException', "Invalid value 'array', string required");
        
        $email = new EmailAddress(array(
            'foo'
        ));
    }
}