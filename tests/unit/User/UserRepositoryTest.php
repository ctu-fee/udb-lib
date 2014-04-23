<?php

namespace UdbTest\Domain\User;

use Udb\Domain\User\UserRepository;


class UserRepositoryTest extends \PHPUnit_Framework_TestCase
{


    public function testConstructor()
    {
        $storage = $this->createStorageMock();
        $hydrator = $this->createHydratorMock();
        $factory = $this->createFactoryMock();
        
        $repository = new UserRepository($storage, $hydrator, $factory);
        
        $this->assertSame($storage, $repository->getStorage());
        $this->assertSame($hydrator, $repository->getHydrator());
        $this->assertSame($factory, $repository->getFactory());
    }


    public function testConstructorWithImplicitDeps()
    {
        $storage = $this->createStorageMock();
        
        $repository = new UserRepository($storage);
        
        $this->assertSame($storage, $repository->getStorage());
        $this->assertInstanceOf('Zend\Stdlib\Hydrator\HydratorInterface', $repository->getHydrator());
        $this->assertInstanceOf('Udb\Domain\User\UserFactoryInterface', $repository->getFactory());
    }


    public function testFetchUserByUid()
    {
        $uid = 'testuser';
        $userData = array(
            'foo' => 'bar'
        );
        $emptyUser = $this->createUserMock();
        $hydratedUser = $this->createUserMock();
        
        $storage = $this->createStorageMock();
        $storage->expects($this->once())
            ->method('fetchUserRecord')
            ->with($uid)
            ->will($this->returnValue($userData));
        
        $factory = $this->createFactoryMock();
        $factory->expects($this->once())
            ->method('createUser')
            ->will($this->returnValue($emptyUser));
        
        $hydrator = $this->createHydratorMock();
        $hydrator->expects($this->once())
            ->method('hydrate')
            ->with($userData, $emptyUser)
            ->will($this->returnValue($hydratedUser));
        
        $repository = new UserRepository($storage, $hydrator, $factory);
        
        $this->assertSame($hydratedUser, $repository->fetchUserByUid($uid));
    }


    public function testFetchUsers()
    {
        $filter = $this->getMock('Udb\Domain\Repository\Filter\FilterInterface');
        $usersData = array(
            array(
                'id' => 'user1'
            ),
            array(
                'id' => 'user2'
            )
        );
        $users = array(
            $this->createUserMock(),
            $this->createUserMock()
        );
        $hydratedUsers = array(
            $this->createUserMock(),
            $this->createUserMock()
        );
        
        $usersCount = count($users);
        
        $storage = $this->createStorageMock();
        $storage->expects($this->once())
            ->method('fetchUserRecords')
            ->with($filter)
            ->will($this->returnValue($usersData));
        
        $factory = $this->createFactoryMock();
        for ($i = 0; $i < $usersCount; $i ++) {
            $factory->expects($this->at($i))
                ->method('createUser')
                ->will($this->returnValue($users[$i]));
        }
        
        $hydrator = $this->createHydratorMock();
        for ($i = 0; $i < $usersCount; $i ++) {
            $hydrator->expects($this->at($i))
                ->method('hydrate')
                ->with($usersData[$i], $users[$i])
                ->will($this->returnValue($hydratedUsers[$i]));
        }
        
        $repository = new UserRepository($storage, $hydrator, $factory);
        
        $this->assertSame($hydratedUsers, $repository->fetchUsers($filter)
            ->toArray());
    }
    
    /*
     * 
     */
    
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createStorageMock()
    {
        $storage = $this->getMock('Udb\Domain\Storage\StorageInterface');
        
        return $storage;
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createFactoryMock()
    {
        return $this->getMock('Udb\Domain\User\UserFactoryInterface');
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createHydratorMock()
    {
        return $this->getMock('Zend\Stdlib\Hydrator\HydratorInterface');
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createUserMock()
    {
        $user = $this->getMock('Udb\Domain\User\User');
        
        return $user;
    }
}