<?php

namespace Udb\Domain\Storage;

use Udb\Domain\Repository\Filter\FilterInterface;


interface GroupStorageInterface
{


    /**
     * Returns a single group record by group name.
     * 
     * @param string $groupName
     * @return array
     */
    public function fetchGroupRecord($groupName);


    /**
     * Returns a list of group records complying with the provided filter.
     * 
     * @param FilterInterface $filter
     * @return array
     */
    public function fetchGroupRecords(FilterInterface $filter = null);


    /**
     * Add a member to the group.
     * 
     * @param string $groupName
     * @param string $uid
     */
    public function addGroupMember($groupName, $uid);


    /**
     * Remove a member from the group.
     * 
     * @param string $groupName
     * @param string $uid
     */
    public function removeGroupMember($groupName, $uid);


    /**
     * Returns the records of the groups, the user is member of.
     *
     * @param string $uid
     * @return array
     */
    public function fetchUserGroupRecords($uid);


    /**
     * Add an owner to the group.
     * 
     * @param string $groupName
     * @param string $uid
     */
    public function addGroupOwner($groupName, $uid);


    /**
     * Remove an owner from the group.
     * 
     * @param string $groupName
     * @param string $uid
     */
    public function removeGroupOwner($groupName, $uid);


    /**
     * Add a new group.
     * 
     * @param string $groupName
     * @param array $data
     */
    public function addGroup($groupName, array $data = array());


    /**
     * Remove a group.
     * 
     * @param string $groupName
     */
    public function removeGroup($groupName);


    public function setGroupAttribute($name, $value);
}