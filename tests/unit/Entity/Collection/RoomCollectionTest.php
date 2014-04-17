<?php

namespace UdbTest\Domain\Entity\Collection;

use Udb\Domain\Entity\Room;
use Udb\Domain\Entity\Collection\RoomCollection;


class RoomCollectionTest extends \PHPUnit_Framework_TestCase
{

    protected $col;


    public function setUp()
    {
        $this->col = new RoomCollection();
    }


    public function testAppendWithObject()
    {
        $room = new Room('123');
        $this->col->append($room);
        
        $this->assertSame($room, $this->col->get(0));
    }


    public function testAppendWithString()
    {
        $value = '123';
        $this->col->append($value);
        
        $this->assertSame($value, $this->col->get(0)
            ->getValue());
    }


    public function testAppendInvalid()
    {
        $this->setExpectedException('Udb\Domain\Entity\Collection\Exception\InvalidItemException', 'Invalid item');
        
        $this->col->append(new \stdClass());
    }
}