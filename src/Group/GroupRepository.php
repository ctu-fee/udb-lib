<?php

namespace Udb\Domain\Group;

use Udb\Domain\Storage\GroupStorageInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Udb\Domain\Repository\Filter\FilterInterface;


class GroupRepository
{

    /**
     * @var GroupStorageInterface
     */
    protected $storage;

    /**
     * @var GroupFactoryInterface
     */
    protected $factory;

    /**
     * @var HydratorInterface
     */
    protected $hydrator;


    /**
     * Constructor.
     * 
     * @param GroupStorageInterface $storage
     * @param HydratorInterface $hydrator
     * @param GroupFactoryInterface $factory
     */
    public function __construct(GroupStorageInterface $storage, HydratorInterface $hydrator = null, GroupFactoryInterface $factory = null)
    {
        $this->setStorage($storage);
        
        if (null !== $hydrator) {
            $this->setHydrator($hydrator);
        }
        
        if (null !== $factory) {
            $this->setFactory($factory);
        }
    }


    /**
     * @return GroupStorageInterface
     */
    public function getStorage()
    {
        return $this->storage;
    }


    /**
     * @param GroupStorageInterface $storage
     */
    public function setStorage(GroupStorageInterface $storage)
    {
        $this->storage = $storage;
    }


    /**
     * @return GroupFactoryInterface
     */
    public function getFactory()
    {
        if (! $this->factory instanceof GroupFactoryInterface) {
            $this->factory = new GroupFactory();
        }
        
        return $this->factory;
    }


    /**
     * @param GroupFactoryInterface $factory
     */
    public function setFactory(GroupFactoryInterface $factory)
    {
        $this->factory = $factory;
    }


    /**
     * @return HydratorInterface
     */
    public function getHydrator()
    {
        if (! $this->hydrator instanceof HydratorInterface) {
            $this->hydrator = new GroupHydrator();
        }
        
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
     * Fetches the corresponding group.
     * 
     * @param string $groupName
     * @return Group
     */
    public function fetchGroup($groupName)
    {
        $record = $this->getStorage()->fetchGroupRecord($groupName);
        $group = $this->createGroup($record);
        
        return $group;
    }


    /**
     * Fetches a collection of groups, which comply with the provided filter.
     * 
     * @param FilterInterface $filter
     * @return \Udb\Domain\Group\GroupCollection
     */
    public function fetchGroups(FilterInterface $filter = null)
    {
        $records = $this->getStorage()->fetchGroupRecords($filter);
        
        $groups = new GroupCollection();
        foreach ($records as $record) {
            $groups->append($this->createGroup($record));
        }
        
        return $groups;
    }


    public function fetchUserGroups(User $user)
    {}


    public function addUserToGroup(User $user, Group $group)
    {}


    public function removeUserFromGroup(User $user, Group $group)
    {}


    public function addOwnerToGroup(User $user, Group $group)
    {}


    public function addGroup(Group $group)
    {}


    public function deleteGroup(Group $group)
    {}


    public function updateGroup(Group $group, array $attributes)
    {}


    protected function createGroup(array $record)
    {
        $group = $this->getFactory()->createGroup();
        $group = $this->getHydrator()->hydrate($record, $group);
        
        return $group;
    }
}