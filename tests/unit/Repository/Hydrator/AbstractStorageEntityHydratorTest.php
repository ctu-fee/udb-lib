<?php

namespace UdbTest\Domain\Repository\Hydrator;


interface DummyClass
{


    public function setName($name);


    public function setMembers(array $members);


    public function setAddress($address);
}


class AbstractStorageEntityHydratorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \Udb\Domain\Repository\Hydrator\AbstractStorageEntityHydrator
     */
    protected $hydrator;


    public function setUp()
    {
        $this->hydrator = $this->getMockForAbstractClass('Udb\Domain\Repository\Hydrator\AbstractStorageEntityHydrator');
    }


    public function testHydrateWithInvalidEntity()
    {
        $this->setExpectedException('Udb\Domain\Entity\Exception\InvalidEntityException', 'Invalid variable/object');
        
        $data = array();
        $entity = new \stdClass();
        
        $this->toggleIsValidEntity($this->hydrator, false);
        $this->hydrator->hydrate($data, $entity);
    }


    public function testHydrateWithUndefinedSetter()
    {
        $this->setExpectedException('Udb\Domain\Repository\Hydrator\Exception\UndefinedSetterException', 'Undefined setter for field');
        
        $data = array(
            'cn' => array(
                0 => 'Foo Bar'
            )
        );
        $map = array(
            'cn' => array()
        );
        
        $entity = $this->createEntityMock();
        
        $this->toggleIsValidEntity($this->hydrator, true);
        $this->hydrator->setFieldMap($map);
        $this->hydrator->hydrate($data, $entity);
    }


    public function testHydrate()
    {
        $map = array(
            'cn' => array(
                'setter' => 'setName'
            ),
            'member' => array(
                'setter' => 'setMembers',
                'multiple' => true
            ),
            'address' => array(
                'setter' => 'setAddress',
                'transformMethod' => 'transformAddress'
            )
        );
        
        $data = array(
            'cn' => array(
                0 => 'Test Name'
            ),
            'member' => array(
                0 => 'member1',
                1 => 'member2'
            ),
            'address' => array()
        );
        
        $entity = $this->createEntityMock();
        $entity->expects($this->once())
            ->method('setName')
            ->with($data['cn'][0]);
        $entity->expects($this->once())
            ->method('setMembers')
            ->with($data['member']);
        $entity->expects($this->never())
            ->method('setAddress');
        
        $this->toggleIsValidEntity($this->hydrator, true);
        $this->hydrator->setFieldMap($map);
        $this->hydrator->hydrate($data, $entity);
    }


    protected function toggleIsValidEntity($hydrator, $valid)
    {
        $valid = (boolean) $valid;
        
        $hydrator->expects($this->any())
            ->method('isValidEntity')
            ->will($this->returnValue($valid));
    }


    protected function createEntityMock()
    {
        $entity = $this->getMock(__NAMESPACE__ . '\DummyClass');
        
        return $entity;
    }
}