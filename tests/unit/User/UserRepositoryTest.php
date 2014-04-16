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


    public function testGetUserById()
    {
        $userId = 123;
        $userData = array(
            'foo' => 'bar'
        );
        $emptyUser = $this->createUserMock();
        $hydratedUser = $this->createUserMock();
        
        $storage = $this->createStorageMock();
        $storage->expects($this->once())
            ->method('fetchUserRecord')
            ->with($userId)
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
        
        $this->assertSame($hydratedUser, $repository->getUserById($userId));
    }
    
    /*
     * 
     */
    
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createStorageMock()
    {
        $storage = $this->getMock('Udb\Domain\User\Storage\StorageInterface');
        
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