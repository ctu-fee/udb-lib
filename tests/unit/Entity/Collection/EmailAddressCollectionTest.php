<?php

namespace UdbTest\Domain\Entity\Collection;

use Udb\Domain\Entity\Phone;
use Udb\Domain\Entity\EmailAddress;
use Udb\Domain\Entity\Collection\EmailAddressCollection;


class EmailAddressCollectionTest extends \PHPUnit_Framework_TestCase
{

    protected $col;


    public function setUp()
    {
        $this->col = new EmailAddressCollection();
    }


    public function testAppendInvalid()
    {
        $this->setExpectedException('Udb\Domain\Entity\Collection\Exception\InvalidItemException', 'Invalid item');
        
        $this->col->append(new Phone('123'));
    }


    public function testAppendObject()
    {
        $email = new EmailAddress('foo@bar');
        
        $this->col->append($email);
        $this->assertSame($email, $this->col->get(0));
    }


    public function testAppendString()
    {
        $value = 'foo@bar';
        
        $this->col->append($value);
        $this->assertSame($value, $this->col->get(0)
            ->getValue());
    }
}