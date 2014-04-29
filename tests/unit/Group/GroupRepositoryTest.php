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
        $hydratedGroup = $this->createGroupMock();
        
        $storage = $this->createStorageMock();
        $storage->expects($this->once())
            ->method('fetchGroupRecord')
            ->with($groupName)
            ->will($this->returnValue($groupRecord));
        
        $factory = $this->createFactoryMock($group);
        $hydrator = $this->createHydratorMock($groupRecord, $group, $hydratedGroup);
        
        $repository = new GroupRepository($storage, $hydrator, $factory);
        
        $this->assertSame($hydratedGroup, $repository->fetchGroup($groupName));
    }


    public function testFetchGroups()
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


    public function testFetchUserGroups()
    {
        $uid = 'testuser';
        
        $user = $this->createUserMock();
        $user->expects($this->once())
            ->method('getUsername')
            ->will($this->returnValue($uid));
        
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
        
        $groups = $this->createGroupCollectionMock();
        
        $storage = $this->createStorageMock();
        $storage->expects($this->once())
            ->method('fetchUserGroupRecords')
            ->with($uid)
            ->will($this->returnValue($records));
        
        $repository = $this->getMockBuilder('Udb\Domain\Group\GroupRepository')
            ->setMethods(array(
            'createGroupCollection'
        ))
            ->disableOriginalConstructor()
            ->getMock();
        $repository->expects($this->once())
            ->method('createGroupCollection')
            ->with($records)
            ->will($this->returnValue($groups));
        $repository->setStorage($storage);
        
        $this->assertSame($groups, $repository->fetchUserGroups($user));
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


    protected function createGroupCollectionMock()
    {
        $groups = $this->getMock('Udb\Domain\Group\GroupCollection');
        
        return $groups;
    }


    protected function createStorageMock()
    {
        $storage = $this->getMock('Udb\Domain\Storage\GroupStorageInterface');
        
        return $storage;
    }


    protected function createHydratorMock($groupRecord = null, $group = null, $hydratedGroup = null)
    {
        $hydrator = $this->getMock('Zend\Stdlib\Hydrator\HydratorInterface');
        if ($groupRecord && $group && $hydratedGroup) {
            $hydrator->expects($this->once())
                ->method('hydrate')
                ->with($groupRecord, $group)
                ->will($this->returnValue($hydratedGroup));
        }
        
        return $hydrator;
    }


    protected function createFactoryMock($group = null)
    {
        $factory = $this->getMock('Udb\Domain\Group\GroupFactoryInterface');
        if ($group) {
            $factory->expects($this->once())
                ->method('createGroup')
                ->will($this->returnValue($group));
        }
        
        return $factory;
    }


    protected function createUserMock()
    {
        $user = $this->getMock('Udb\Domain\User\User');
        
        return $user;
    }
}