<?php

namespace Udb\Domain\Storage;

use Udb\Domain\Repository\Filter\FilterInterface;


interface StorageInterface
{


    /**
     * Returns true, if the storage supports proxy authentication.
     *
     * @return boolean
     */
    public function supportsProxyAuthentication();


    /**
     * Sets a proxy user authorization by UID.
     *
     * @param string $uid
     * @return boolean
     */
    public function setProxyUserByUid($uid);


    /**
     * Returns a single user record.
     * 
     * @param string $uid
     * @return array
     */
    public function fetchUserRecord($uid);


    /**
     * Updates a user record.
     * 
     * @param string $uid
     * @param array $data List of attributes to be updated.
     */
    public function updateUserRecord($uid, array $data);


    /**
     * Returns a list of user records, complying with the provided filter.
     * 
     * @param FilterInterface $filter
     * @return array
     */
    public function fetchUserRecords(FilterInterface $filter = null);


    /**
     * Returns the records of the groups, the user is member of.
     * 
     * @param string $uid
     * @return array
     */
    public function fetchUserGroupRecords($uid);


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
     * Returns all group members' records.
     * 
     * @param string $groupName
     * @return array
     */
    public function fetchGroupMemberRecords($groupName);


    public function addGroupMember($groupName, $uid);


    public function removeGroupMember($groupName, $uid);


    public function addGroupOwner($groupName, $uid);


    public function removeGroupOwner($groupName, $uid);


    public function addGroup($groupName, array $data);


    public function removeGroup($groupName);


    public function setGroupAttribute($name, $value);
}