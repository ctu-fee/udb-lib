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


    public function testAppendInvalid()
    {
        $this->setExpectedException('Udb\Domain\Entity\Collection\Exception\InvalidItemException', 'Invalid item');
        
        $this->col->append(new EmailAddress('foo@bar'));
    }


    public function testAppendObject()
    {
        $phone = new Phone('123');
        
        $this->col->append($phone);
        $this->assertSame($phone, $this->col->get(0));
    }


    public function testAppendString()
    {
        $value = '123';
        
        $this->col->append($value);
        $this->assertSame($value, $this->col->get(0)
            ->getValue());
    }


    public function testSetItemsWithArrayOfStrings()
    {
        $data = array(
            '+111 222 333 444',
            '+123-456-789-000'
        );
        
        $this->col->setItems($data);
        
        $this->assertSame($data, $this->col->toPlainArray());
    }
}