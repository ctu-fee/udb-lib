<?php

namespace UdbTest\Domain\Group;

use Udb\Domain\Group\GroupFactory;


class GroupFactoryTest extends \PHPUnit_Framework_TestCase
{


    public function testCreateGroup()
    {
        $factoy = new GroupFactory();
        
        $this->assertInstanceOf('Udb\Domain\Group\Group', $factoy->createGroup());
    }
}