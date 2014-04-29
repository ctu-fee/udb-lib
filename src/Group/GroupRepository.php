<?php

namespace Udb\Domain\Group;

use Udb\Domain\Storage\GroupStorageInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Udb\Domain\Repository\Filter\FilterInterface;
use Udb\Domain\User\User;


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
     * @return GroupCollection
     */
    public function fetchGroups(FilterInterface $filter = null)
    {
        $records = $this->getStorage()->fetchGroupRecords($filter);
        $groups = $this->createGroupCollection($records);
        
        return $groups;
    }


    /**
     * Returns a collection of groups, the provided user is member of.
     * 
     * @param User $user
     * @return GroupCollection
     */
    public function fetchUserGroups(User $user)
    {
        $records = $this->getStorage()->fetchUserGroupRecords($user->getUsername());
        $groups = $this->createGroupCollection($records);
        
        return $groups;
    }


    /**
     * Adds the user to the group.
     * 
     * @param User $user
     * @param Group $group
     */
    public function addUserToGroup(User $user, Group $group)
    {
        $this->getStorage()->addGroupMember($group->getName(), $user->getUsername());
    }


    /**
     * Removes the user from the group.
     * 
     * @param User $user
     * @param Group $group
     */
    public function removeUserFromGroup(User $user, Group $group)
    {
        $this->getStorage()->removeGroupMember($group->getName(), $user->getUsername());
    }


    /**
     * Adds the user as an owner of the group.
     * 
     * @param User $user
     * @param Group $group
     */
    public function addOwnerToGroup(User $user, Group $group)
    {
        $this->getStorage()->addGroupOwner($group->getName(), $user->getUsername());
    }


    /**
     * Removes the user from the owner list of the group.
     * 
     * @param User $user
     * @param Group $group
     */
    public function removeOwnerFromGroup(User $user, Group $group)
    {
        $this->getStorage()->removeGroupOwner($group->getName(), $user->getUsername());
    }


    public function addGroup(Group $group)
    {}


    public function deleteGroup(Group $group)
    {}


    public function updateGroup(Group $group, array $attributes)
    {}


    /**
     * Creates a group entity and hydrates it with the provided record data.
     * 
     * @param array $record
     * @return Group
     */
    protected function createGroup(array $record)
    {
        $group = $this->getFactory()->createGroup();
        $group = $this->getHydrator()->hydrate($record, $group);
        
        return $group;
    }


    /**
     * Creates a group collection from the provided records.
     * 
     * @param array $records
     * @return GroupCollection
     */
    protected function createGroupCollection(array $records)
    {
        $groups = new GroupCollection();
        foreach ($records as $record) {
            $groups->append($this->createGroup($record));
        }
        
        return $groups;
    }
}