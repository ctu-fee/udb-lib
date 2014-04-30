<?php

namespace UdbTest\Domain\Repository\Hydrator;


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


    
    public function testHydrate()
    {
        $this->markTestIncomplete();
        $map = array(
            'cn' => array(
                'method' => 'setName'
            )
        );
        
        $data = array(
            'cn' => array(
                0 => 'Test Name'
            )
        );
        
        $entity = $this->getMock('stdClass');
        $entity->expects($this->once())
            ->method('setName')
            ->with('Test Name');
        
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
}