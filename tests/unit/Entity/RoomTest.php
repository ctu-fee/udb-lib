<?php

namespace UdbTest\Domain\Entity;

use Udb\Domain\Entity\Room;


class RoomTest extends \PHPUnit_Framework_TestCase
{


    public function testConstructor()
    {
        $value = 'room, definition, with address';
        
        $room = new Room($value);
        $this->assertSame($value, $room->getValue());
    }


    public function testConstructorWithInvalidValue()
    {
        $this->setExpectedException('Udb\Domain\Entity\Exception\InvalidValueException', 'Invalid value');
        
        $value = array(
            'room'
        );
        $room = new Room($value);
    }


    public function testConstructorWithEmptyString()
    {
        $this->setExpectedException('Udb\Domain\Entity\Exception\InvalidValueException', 'Empty string');
        
        $room = new Room('');
    }
}