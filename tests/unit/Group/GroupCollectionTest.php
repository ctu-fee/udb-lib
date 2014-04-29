<?php

namespace UdbTest\Domain\Group;

use Udb\Domain\Group\GroupCollection;


class GroupCollectionTest extends \PHPUnit_Framework_TestCase
{


    public function testAppendInvalid()
    {
        $this->setExpectedException('Udb\Domain\Entity\Collection\Exception\InvalidItemException', 'Invalid item');
        
        $groups = new GroupCollection();
        $groups->append(new \stdClass());
    }


    public function testAppend()
    {
        $groups = new GroupCollection();
        
        $group1 = $this->createGroupMock();
        $group2 = $this->createGroupMock();
        
        $groups->append($group1);
        $groups->append($group2);
        
        $this->assertSame($group1, $groups->get(0));
        $this->assertSame($group2, $groups->get(1));
    }
    
    /*
     * 
     */
    protected function createGroupMock()
    {
        $group = $this->getMock('Udb\Domain\Group\Group');
        
        return $group;
    }
}