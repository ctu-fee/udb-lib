<?php

namespace UdbTest\Domain\Entity\Collection;

use Udb\Domain\Entity\Uid;
use Udb\Domain\Entity\Collection\UidCollection;


class UidCollectionTest extends \PHPUnit_Framework_TestCase
{

    protected $col;


    public function setUp()
    {
        $this->col = new UidCollection();
    }


    public function testAppendInvalid()
    {
        $this->setExpectedException('Udb\Domain\Entity\Collection\Exception\InvalidItemException', 'Invalid item');
        
        $this->col->append(new \stdClass());
    }


    public function testAppendString()
    {
        $uid = 'testuser12';
        $this->col->append($uid);
        
        $this->assertSame($uid, $this->col->get(0)
            ->getValue());
    }


    public function testAppendObject()
    {
        $uid = new Uid('testuser12');
        $this->col->append($uid);
        
        $this->assertSame($uid, $this->col->get(0));
    }


    public function testSetItemsWithArrayOfStrings()
    {
        $uidList = array(
            'testuser1',
            'testuser2'
        );
        
        $this->col->setItems($uidList);
        
        $this->assertSame($uidList, $this->col->toPlainArray());
    }
}