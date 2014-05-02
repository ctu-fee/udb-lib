<?php

namespace UdbTest\Domain\Repository\Hydrator;


interface DummyInterface
{


    public function getName();


    public function setName($name);


    public function getMembers();


    public function setMembers(array $members);


    public function getAddress();


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


    public function testExtract()
    {
        $name = 'Foo Bar';
        $members = array(
            'member1',
            'member2'
        );
        
        $map = array(
            'cn' => array(
                'getter' => 'getName'
            ),
            'member' => array(
                'getter' => 'getMembers',
                'multiple' => true
            ),
            'address' => array(
                'getter' => 'getAddress'
            )
        );
        
        $entity = $this->createEntityMock();
        $entity->expects($this->once())
            ->method('getName')
            ->will($this->returnValue($name));
        $entity->expects($this->once())
            ->method('getMembers')
            ->will($this->returnValue($members));
        $entity->expects($this->once())
            ->method('getAddress')
            ->will($this->returnValue(null));
        
        $this->toggleIsValidEntity($this->hydrator, true);
        $this->hydrator->setFieldMap($map);
        
        $data = $this->hydrator->extract($entity);
        $expectedData = array(
            'cn' => array(
                0 => $name
            ),
            'member' => $members
        );
        
        $this->assertEquals($expectedData, $data);
    }


    public function testExtractWithUndefinedEntityGetterMethod()
    {
        $this->setExpectedException('Udb\Domain\Repository\Hydrator\Exception\UndefinedMethodException', 'Undefined method');
        
        $map = array(
            'foo' => array(
                'getter' => 'getFoo'
            )
        );
        $data = array(
            'foo' => array(
                'bar'
            )
        );
        
        $this->toggleIsValidEntity($this->hydrator, true);
        $this->hydrator->setFieldMap($map);
        
        $entity = $this->createEntityMock();
        
        $this->hydrator->extract($entity);
    }
    
    /*
     * 
     */
    protected function toggleIsValidEntity($hydrator, $valid)
    {
        $valid = (boolean) $valid;
        
        $hydrator->expects($this->any())
            ->method('isValidEntity')
            ->will($this->returnValue($valid));
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createEntityMock()
    {
        $entity = $this->getMock(__NAMESPACE__ . '\DummyInterface');
        
        return $entity;
    }
}