<?php

namespace Udb\Domain\User;

use Udb\Domain\User\Filter\FilterInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Udb\Domain\User\Storage\StorageInterface;


class UserRepository
{

    /**
     * @var StorageInterface
     */
    protected $storage;

    /**
     * @var HydratorInterface
     */
    protected $hydrator;

    /**
     * @var UserFactoryInterface
     */
    protected $factory;


    /**
     * Constructor.
     * 
     * @param StorageInterface $storage
     * @param HydratorInterface $hydrator
     * @param UserFactoryInterface $factory
     */
    public function __construct(StorageInterface $storage, HydratorInterface $hydrator = null, 
        UserFactoryInterface $factory = null)
    {
        $this->setStorage($storage);
        
        if (null === $hydrator) {
            $hydrator = new UserHydrator();
        }
        $this->setHydrator($hydrator);
        
        if (null === $factory) {
            $factory = new UserFactory();
        }
        $this->setFactory($factory);
    }


    /**
     * @return StorageInterface
     */
    public function getStorage()
    {
        return $this->storage;
    }


    /**
     * @param StorageInterface $storage
     */
    public function setStorage(StorageInterface $storage)
    {
        $this->storage = $storage;
    }


    /**
     * @return HydratorInterface
     */
    public function getHydrator()
    {
        return $this->hydrator;
    }


    /**
     * @param HydratorInterface $hydrator
     */
    public function setHydrator(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
    }


    /**
     * @return UserFactoryInterface
     */
    public function getFactory()
    {
        return $this->factory;
    }


    /**
     * @param UserFactoryInterface $factory
     */
    public function setFactory(UserFactoryInterface $factory)
    {
        $this->factory = $factory;
    }


    /**
     * Fetches the user with the provided UID.
     * 
     * @param string $uid
     * @return User
     */
    public function fetchUserByUid($uid)
    {
        $userData = $this->getStorage()->fetchUserRecord($uid);
        
        return $this->createUser($userData);
    }


    public function fetchUsers(FilterInterface $filter)
    {
        $usersData = $this->getStorage()->fetchUserRecords($filter);
        
        $users = new UserCollection();
        foreach ($usersData as $userData) {
            $users->append($this->createUser($userData));
        }
        
        return $users;
    }


    protected function createUser(array $userData = array())
    {
        $user = $this->getFactory()->createUser();
        $user = $this->getHydrator()->hydrate($userData, $user);
        
        return $user;
    }
}