<?php

namespace UdbTest\Domain\Entity\Collection;

use Udb\Domain\Entity\EmailAddress;
use Udb\Domain\Entity\Phone;
use Udb\Domain\Entity\Collection\PhoneCollection;


class PhoneCollectionTest extends \PHPUnit_Framework_TestCase
{

    protected $col;


    public function setUp()
    {
        $this->col = new PhoneCollection();
    }


    public function testIsValidWithValidItem()
    {
        $this->assertTrue($this->col->isValid(new Phone('123')));
    }


    public function testIsValidWithInvalidItem()
    {
        $this->assertFalse($this->col->isValid(new EmailAddress('foo@bar')));
    }


    public function testToPlainArray()
    {
        $this->col->append(new Phone('123'));
        $this->col->append(new Phone('456'));
        
        $this->assertSame(array(
            '123',
            '456'
        ), $this->col->toPlainArray());
    }


    public function testSetWithArrayOfStrings()
    {
        $data = array(
            '+111 222 333 444',
            '+123-456-789-000'
        );
        
        $this->col->setItems($data);
        
        $this->assertSame($data, $this->col->toPlainArray());
    }
}