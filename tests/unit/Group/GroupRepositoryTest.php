<?php

namespace UdbTest\Domain\Group;

use Udb\Domain\Group\GroupRepository;


class GroupRepositoryTest extends \PHPUnit_Framework_TestCase
{


    public function testConstructor()
    {
        $storage = $this->createStorageMock();
        $hydrator = $this->createHydratorMock();
        $factory = $this->createFactoryMock();
        
        $repository = new GroupRepository($storage, $hydrator, $factory);
        
        $this->assertSame($storage, $repository->getStorage());
        $this->assertSame($hydrator, $repository->getHydrator());
        $this->assertSame($factory, $repository->getFactory());
    }


    public function testConstructorWithImplicitValues()
    {
        $storage = $this->createStorageMock();
        $repository = new GroupRepository($storage);
        
        $this->assertSame($storage, $repository->getStorage());
        $this->assertInstanceOf('Zend\Stdlib\Hydrator\HydratorInterface', $repository->getHydrator());
        $this->assertInstanceOf('Udb\Domain\Group\GroupFactoryInterface', $repository->getFactory());
    }


    public function testFetchGroup()
    {
        $groupName = 'Test Group';
        $groupRecord = array(
            'foo' => 'bar'
        );
        $group = $this->createGroupMock();
        
        $storage = $this->createStorageMock();
        $storage->expects($this->once())
            ->method('fetchGroupRecord')
            ->with($groupName)
            ->will($this->returnValue($groupRecord));
        
        $repository = $this->getMockBuilder('Udb\Domain\Group\GroupRepository')
            ->setMethods(array(
            'createGroup'
        ))
            ->disableOriginalConstructor()
            ->getMock();
        $repository->expects($this->once())
            ->method('createGroup')
            ->with($groupRecord)
            ->will($this->returnValue($group));
        
        $repository->setStorage($storage);
        
        $this->assertSame($group, $repository->fetchGroup($groupName));
    }


    public function testFetchUserGroups()
    {
        $filter = $this->createFilterMock();
        $records = array(
            array(
                'cn' => array(
                    0 => 'Test Group #1'
                )
            ),
            array(
                'cn' => array(
                    0 => 'Test Group #2'
                )
            )
        );
        $groups = array(
            $this->createGroupMock(),
            $this->createGroupMock()
        );
        $hydratedGroups = array(
            $this->createGroupMock(),
            $this->createGroupMock()
        );
        
        $storage = $this->createStorageMock();
        $storage->expects($this->once())
            ->method('fetchGroupRecords')
            ->with($filter)
            ->will($this->returnValue($records));
        
        $factory = $this->createFactoryMock();
        $hydrator = $this->createHydratorMock();
        
        for ($i = 0; $i < count($records); $i ++) {
            $factory->expects($this->at($i))
                ->method('createGroup')
                ->will($this->returnValue($groups[$i]));
            $hydrator->expects($this->at($i))
                ->method('hydrate')
                ->with($records[$i], $groups[$i])
                ->will($this->returnValue($hydratedGroups[$i]));
        }
        
        $repository = new GroupRepository($storage, $hydrator, $factory);
        
        $resultGroups = $repository->fetchGroups($filter);
        
        $this->assertInstanceOf('Udb\Domain\Group\GroupCollection', $resultGroups);
        $this->assertSame($hydratedGroups[0], $resultGroups->get(0));
        $this->assertSame($hydratedGroups[1], $resultGroups->get(1));
    }
    
    /*
     * 
     */
    protected function createFilterMock()
    {
        $filter = $this->getMock('Udb\Domain\Repository\Filter\FilterInterface');
        
        return $filter;
    }


    protected function createGroupMock()
    {
        $group = $this->getMock('Udb\Domain\Group\Group');
        
        return $group;
    }


    protected function createStorageMock()
    {
        $storage = $this->getMock('Udb\Domain\Storage\GroupStorageInterface');
        
        return $storage;
    }


    protected function createHydratorMock()
    {
        $hydrator = $this->getMock('Zend\Stdlib\Hydrator\HydratorInterface');
        
        return $hydrator;
    }


    protected function createFactoryMock()
    {
        $factory = $this->getMock('Udb\Domain\Group\GroupFactoryInterface');
        
        return $factory;
    }
}