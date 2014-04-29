<?php

namespace Udb\Domain\Storage;

use Udb\Domain\Repository\Filter\FilterInterface;


interface UserStorageInterface
{


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
     * Returns all group members' records.
     *
     * @param string $groupName
     * @return array
     */
    public function fetchGroupMemberRecords($groupName);


    /**
     * Fetch the owners of the group.
     *
     * @param string $groupName
     * @return array
     */
    public function fetchGroupOwnerRecords($groupName);
}