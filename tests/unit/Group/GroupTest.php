<?php

namespace UdbTest\Domain\Group;

use Udb\Domain\Group\Group;


class GroupTest extends \PHPUnit_Framework_TestCase
{


    public function testGettersAndSetters()
    {
        $name = 'Test Group';
        $description = 'Test Group description';
        $email = 'test.group@example.org';
        $ownerUid = 'testuser';
        
        $group = new Group();
        $group->setName($name);
        $group->setDescription($description);
        $group->setEmail($email);
        $group->setOwnerUid($ownerUid);
        
        $this->assertSame($name, $group->getName());
        $this->assertSame($description, $group->getDescription());
        $this->assertSame($email, $group->getEmail());
        $this->assertSame($ownerUid, $group->getOwnerUid());
    }
}