<?php

namespace UdbTest\Domain\Group;

use Udb\Domain\Entity\Collection\EmailAddressCollection;
use Udb\Domain\Entity\Collection\UidCollection;
use Udb\Domain\Group\Group;


class GroupTest extends \PHPUnit_Framework_TestCase
{


    public function testGettersAndSetters()
    {
        $name = 'Test Group';
        $description = 'Test Group description';
        $email = 'test.group@example.org';
        $owners = new UidCollection();
        $departmentNumber = '12345';
        $dynamic = true;
        $emailAlternatives = new EmailAddressCollection();
        
        $group = new Group();
        $group->setName($name);
        $group->setDescription($description);
        $group->setDepartmentNumber($departmentNumber);
        $group->setEmail($email);
        $group->setOwners($owners);
        $group->setDynamic($dynamic);
        $group->setEmailAlternatives($emailAlternatives);
        
        $this->assertSame($name, $group->getName());
        $this->assertSame($description, $group->getDescription());
        $this->assertSame($departmentNumber, $group->getDepartmentNumber());
        $this->assertSame($email, $group->getEmail());
        $this->assertSame($owners, $group->getOwners());
        $this->assertSame($dynamic, $group->isDynamic());
        $this->assertSame($emailAlternatives, $group->getEmailAlternatives());
    }


    public function testSetOwnersWithArray()
    {
        $owners = array(
            'testuser1',
            'testuser2'
        );
        $group = new Group();
        $group->setOwners($owners);
        
        $this->assertSame($owners, $group->getOwners()
            ->toPlainArray());
    }


    public function testSetEmailAlternativesWithArray()
    {
        $emails = array(
            'foo@bar'
        );
        $group = new Group();
        $group->setEmailAlternatives($emails);
        
        $this->assertSame($emails, $group->getEmailAlternatives()->toPlainArray());
    }
}