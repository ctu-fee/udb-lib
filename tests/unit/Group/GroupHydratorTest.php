<?php

namespace UdbTest\Domain\Group;

use Udb\Domain\Group\GroupHydrator;
use Udb\Domain\Group\Group;


class GroupHydratorTest extends \PHPUnit_Framework_TestCase
{


    public function testHydrateWithInvalidEntity()
    {
        $this->setExpectedException('Udb\Domain\Entity\Exception\InvalidEntityException', 'Invalid variable/object');
        
        $hydrator = new GroupHydrator();
        $hydrator->hydrate(array(), new \stdClass());
    }


    public function testHydrate()
    {
        $hydrator = new GroupHydrator();
        
        $data = $this->getTestGroupData();
        $group = $hydrator->hydrate($data, new Group());
        
        $this->assertSame($data['cn'][0], $group->getName());
        $this->assertSame($data['description'][0], $group->getDescription());
        $this->assertSame($data['mail'][0], $group->getEmail());
        $this->assertSame('testuser', $group->getOwnerUid());
    }


    public function testHydrateWithInvalidOwnerDn()
    {
        $this->setExpectedException('Udb\Domain\Group\Exception\InvalidGroupDnException', 'Invalid group DN format');
        
        $hydrator = new GroupHydrator();
        $data = $this->getTestGroupData();
        $data['owner'][0] = 'some random string';
        
        $group = $hydrator->hydrate($data, new Group());
    }
    
    /*
     * 
     */
    protected function getTestGroupData()
    {
        return array(
            'dn' => 'cn=Test Group,ou=test,ou=Groups,o=feld.cvut.cz',
            'cn' => array(
                0 => 'Test Group'
            ),
            'description' => array(
                0 => 'Some description'
            ),
            'mail' => array(
                0 => 'test@example.org'
            ),
            'owner' => array(
                0 => 'uid=testuser,ou=people,o=example.org'
            ),
            'objectclass' => array(
                0 => 'groupOfUniqueNames',
                1 => 'top'
            )
        );
    }
}